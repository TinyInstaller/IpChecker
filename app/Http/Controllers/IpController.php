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
        $ipInfo->append('residential');
        $merged=[];
        $json=$ipInfo->mapWithKeys(function($item)use(&$merged){
            foreach ($item->toArray() as $key=>$value){
                if(!isset($merged[$key])){
                    if(isset($value) && $value!=='n/a') {
                        $merged[$key] = $value;
                    }else{
                        $merged[$key]='';
                    }
                }
            }
            return [$item->provider=>$item];
        })->toArray();
        $json['all']=$merged;
        return response()->json($json,200,[
            //Cross-Origin Resource Sharing
            'Access-Control-Allow-Origin'=>'*'
        ]);
    }
}
