<?php

namespace App\Http\Controllers;

use App\Models\IpGeolocation;
use App\Services\IpApiService;

class IpController extends Controller
{
    protected $ipApiService;
    public function __construct(IpApiService $ipApiService)
    {
        $this->ipApiService=$ipApiService;
    }

    function index($ip=null)
    {
        //Expect json
        request()->headers->set('Accept','application/json');
        if(!$ip) {
            $ip = request('ip',request()->ip());
        }
        try {
            $this->validate(request()->merge(['ip' => $ip]), ['ip' => 'ip']);
        }catch (\Exception $e){
            return response()->json(['error'=>'Invalid IP address']);
        }

        if(!$ipInfo=IpGeolocation::query()->where('ip',$ip)->first()) {
            $ipInfoFromApi=$this->ipApiService->getInfoPremium($ip);
            if(!$ipInfoFromApi){
                $ipInfoFromApi=$this->ipApiService->getInfo($ip);
            }
            if(!$ipInfoFromApi){
                return response()->json(['error'=>'No data found']);
            }
            $ipInfoFromApi['ip']=$ip;
            $ipInfoFromApi['provider']='ip-api.com';
            IpGeolocation::query()->create($ipInfoFromApi);
            $ipInfo=IpGeolocation::query()->where('ip',$ip)->first();
        }
        $ipInfo->makeHidden(['id','provider','created_at','updated_at']);
        return response()->json($ipInfo);
    }
}
