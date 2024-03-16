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

        $ipInfo=$this->ipApiService->getIpGeolocation($ip);
        $ipInfo->makeHidden(['id','provider','created_at','updated_at']);
        return response()->json($ipInfo);
    }
}
