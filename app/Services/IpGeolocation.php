<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class IpGeolocation
{
    protected $ip;
    protected $endpoint = 'https://api.fdev.top/v1/ip';
    protected static $ipInfoCache = [];
    public function __construct()
    {
        $this->ip = $_SERVER['HTTP_X_REAL_IP']??$_SERVER['REMOTE_ADDR'];
    }
    public function getIpInfo($ip=null)
    {
        if($ip){
            $this->ip = $ip;
        }
        if(!$ip){
            return ['ip'=>$this->ip];
        }
        return Cache::remember('ipinfo_'.$ip, 3600, function () use ($ip) {
            return $this->getIpInfoFromApi($ip);
        });
    }
    public function getIpInfoFromApi($ip=null)
    {
        if($ip){
            $this->ip = $ip;
        }
        if(isset(self::$ipInfoCache[$this->ip])){
            return self::$ipInfoCache[$this->ip];
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->endpoint."?ip=".$this->ip,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response= json_decode($response, true);
        self::$ipInfoCache[$this->ip] = $response;
        try{
             $host = $this->getHostByAddr($this->ip);
        }catch(\Exception $e){
            $host = '';
        }
        if($host===$ip){
            $host = '';
        }
        self::$ipInfoCache[$this->ip]['hostname'] = $host;

        return self::$ipInfoCache[$this->ip];
    }
    protected function getHostByAddr($ip)
    {
        return Cache::remember('hostbyaddr_'.$ip, 3600, function () use ($ip) {
            return $this->findHostByAddr($ip);
        });
    }
    function findHostByAddr($ip, $dns = '8.8.8.8', $timeout = 1 ) {

        static $ips = array();

        if ( isset( $ips[ $ip ] ) ) return $ips[ $ip ];
        else $ips[ $ip ] = $ip;

        // random transaction number (for routers etc to get the reply back)
        $tx = rand( 10, 77 ) . "\1\0\0\1\0\0\0\0\0\0";

        // octals in the array, keys are strlen of bit
        $bitso = array( "", "\1", "\2", "\3" );
        $arpa = '';
        foreach( array_reverse( explode( '.', $ip ) ) as $bit ) {
            $arpa .= $bit . '.';
            $l = strlen( $bit );
            $tx .= "{$bitso[$l]}" . $bit;
        }
        $arpa .= 'in-addr.arpa';

        // and the final bit of the request
        $tx .= "\7in-addr\4arpa\0\0\x0C\0\1";

        // create UDP socket
        $errno = $errstr = 0;
        $fp = fsockopen( "udp://{$dns}", 53, $errno, $errstr, 0.5 );
        if( ! $fp || ! is_resource( $fp ) ) return ( $ips[ $ip ] = $ip );


        if( function_exists( 'socket_set_timeout' ) ) {
            socket_set_timeout( $fp, $timeout );
        } elseif ( function_exists( 'stream_set_timeout' ) ) {
            stream_set_timeout( $fp, $timeout );
        }



        // send our request (and store request size so we can cheat later)
        $tx_size = fwrite( $fp, $tx );
        $max_rx = $tx_size * 7;

        $start = time();
        $rx_size = $res_len = $stop_at = 0;
        $rx = '';

        while (
            (
                ( $stop_at > 0 && $rx_size < $stop_at ) || $stop_at == 0
            )
            && ! feof( $fp )
            && $rx_size < $max_rx
            && ( ( time() - $start ) < $timeout )
            && ($b = fread( $fp, 1 ) ) !== false
        ) {
            $rx_size++;
            $rx .= $b;

            if ( $stop_at == 0 && $res_len == 0 && ( $rx_size > $tx_size + 12 ) ) {
                $res_len = hexdec( bin2hex( substr( $rx, $tx_size + 11, 1 ) ) );
                $stop_at = ( $res_len + $tx_size + 12 );
            }
        }

        // hope we get a reply
        if ( is_resource( $fp ) ) fclose( $fp );

        // if empty response or bad response, return original ip
        if ( empty( $rx ) || bin2hex( substr( $rx, $tx_size + 2, 2 ) ) != '000c' || bin2hex( substr( $rx, -1 ) ) != '00' ) {
            return ( $ips[ $ip ] = $ip );
        }

        // set up our variables
        $host = '';
        $len = $loops = 0;

        // set our pointer at the beginning of the hostname uses the request size from earlier rather than work it out
        $pos = $tx_size + 12;
        do {
            $myc = substr( $rx, $pos, 1 );

            // get segment size
            if ( strlen( $myc ) > 0 ) $len = unpack( 'c', $myc );

            // null terminated string, so length 0 = finished - return the hostname, without the trailing .
            if ( $len[1] == 0 ) return ( $ips[ $ip ] = substr( $host, 0, -1 ) );

            // add segment to our host
            $host .= substr( $rx, $pos + 1, abs( $len[1] ) ) . '.';
            //ISCLOG::l($loops . ': '. 'pos:'.$pos. ' len:' . $len[1] . ' ' . $host);

            // move pointer on to the next segment
            $pos += $len[1] + 1;

        } while ( $len[1] != 0 && $loops++ < 40 );

        // return the ip in case
        return ( $ips[ $ip ] = $ip );
    }
}
