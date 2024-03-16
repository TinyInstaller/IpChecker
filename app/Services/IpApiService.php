<?php

namespace App\Services;

use App\Models\IpGeolocation;
use App\Services\Ip\IpApiComService;

class IpApiService
{
    protected $ipApiCom;
    public function __construct(IpApiComService $ipApiCom)
    {
        $this->ipApiCom=$ipApiCom;
    }

    /**
     * @param $ip
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getIpGeolocation($ip)
    {
        $providers=[
            $this->ipApiCom->getProvider()=>$this->ipApiCom,
        ];
        foreach ($providers as $provider=>$service){
            $geoLocation=IpGeolocation::query()->where(['ip'=>$ip,'provider'=>$provider])->first();
            if(!$geoLocation){
                try{
                    $geoLocation=$service->getGeolocation($ip,$provider);
                    $geoLocation->save();
                }catch (\Exception $e){
                    //continue;
                }
            }
        }
        $geoLocations=IpGeolocation::query()->where(['ip'=>$ip])->get();
        return $geoLocations;
    }
}
