<?php

namespace App\Services;

use App\Models\IpGeolocation;
use App\Services\Ip\IpApiComService;
use App\Services\Ip\IpInfoIoService;
use App\Services\Ip\IpService;

class IpApiService
{

    protected $providers=[
        IpApiComService::class,
        IpInfoIoService::class
    ];

    /**
     * @param $provider
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|mixed|IpApiComService|IpService
     */
    protected function buildProvider($provider){
        return app($provider);
    }


    /**
     * @param $ip
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getIpGeolocation($ip)
    {
        $providers=[];
        foreach ($this->providers as $provider){
            $provider=$this->buildProvider($provider);
            $providers[$provider->getProvider()]=$provider;
        }
        foreach ($providers as $provider=>$service){
            $geoLocation=IpGeolocation::query()->where(['ip'=>$ip,'provider'=>$provider])->first();
            if(!$geoLocation){
                try{
                    $geoLocation=$service->getGeolocation($ip);
                    $geoLocation->save();
                }catch (\Exception $e){
                    echo $e->getMessage();
                    //continue;
                }
            }
        }
        $geoLocations=IpGeolocation::query()->where(['ip'=>$ip])->get();
        return $geoLocations;
    }
}
