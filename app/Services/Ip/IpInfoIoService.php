<?php

namespace App\Services\Ip;

use App\Models\IpGeolocation;
use App\Support\Country;

class IpInfoIoService extends IpService
{
    public function getProvider()
    {
        return 'ipinfo.io';
    }
    public function getGeolocation($ip): IpGeolocation
    {
        $ipGeolocation = new IpGeolocation();
        if(!$this->apiKey){
            throw new \Exception('No API key provided');
        }
        $info=$this->getInfo($ip);
        if(!$info){
            throw new \Exception('No data found');
        }
        //Sample response
        /**
         * {
         * ip: "8.8.8.8",
         * hostname: "dns.google",
         * anycast: true,
         * city: "Mountain View",
         * region: "California",
         * country: "US",
         * loc: "37.4056,-122.0775",
         * org: "AS15169 Google LLC",
         * postal: "94043",
         * timezone: "America/Los_Angeles"
         * }
         */
        $ipGeolocation->ip=$ip;
        $ipGeolocation->provider=$this->getProvider();
        $ipGeolocation->hostname=$info['hostname']??null;
        $ipGeolocation->city=$info['city']??null;
        $ipGeolocation->regionName=$info['region']??null;
        $ipGeolocation->countryCode=$info['country']??null;
        $ipGeolocation->country=Country::getName($ipGeolocation->countryCode);
        $ipGeolocation->lat=(float)explode(',',$info['loc'])[0];
        $ipGeolocation->lon=(float)explode(',',$info['loc'])[1];
        $ipGeolocation->timezone=$info['timezone'];
        $ipGeolocation->zip=$info['postal']??null;
        $ipGeolocation->as=$info['org']??null;
        //$ipGeolocation->org=$info['org'];

        return $ipGeolocation;
    }

    public function getInfo($ip)
    {
        $url = "https://ipinfo.io/$ip/json?token={$this->apiKey}";
        $response = file_get_contents($url);
        $info= json_decode($response,true);
        if(isset($info['ip'])){
            return $info;
        }
        return [];
    }

}
