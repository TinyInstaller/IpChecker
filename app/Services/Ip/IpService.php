<?php

namespace App\Services\Ip;

use App\Models\IpGeolocation;

abstract class IpService
{
    protected $config;
    protected $apiKey;
    public function __construct()
    {
        $this->config=config('services.'.$this->getProvider(),[]);
        $this->apiKey=$this->config['key']??null;
    }
    abstract public function getProvider();
    abstract public function getGeolocation($ip) : IpGeolocation;
}
