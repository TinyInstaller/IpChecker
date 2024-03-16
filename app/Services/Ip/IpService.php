<?php

namespace App\Services\Ip;

use App\Models\IpGeolocation;

abstract class IpService
{
    abstract public function getProvider();
    abstract public function getGeolocation($ip) : IpGeolocation;
}
