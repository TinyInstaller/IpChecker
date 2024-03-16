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
        if(request()->host()!=='ip.fdev.top'){
            $this->endpoint=route('api.ip');
        }
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

        return self::$ipInfoCache[$this->ip];
    }

}
