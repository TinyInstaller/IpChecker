<?php

namespace App\Services;

class IpApiService
{
    protected $apiKey='';
    public function __construct()
    {
        $this->apiKey=config('services.ip-api.key');
    }

    public function getInfo($ip)
    {
        $url = "http://ip-api.com/json/$ip";
        $response = file_get_contents($url);
        return json_decode($response,true);
    }
    public function getInfoPremium($ip)
    {
        if(!$this->apiKey){
            return null;
        }
        $url = "https://pro.ip-api.com/json/$ip?fields=66842623&key=$this->apiKey";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}
