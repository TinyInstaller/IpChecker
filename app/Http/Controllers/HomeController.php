<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $ipGeolocation;

    public function __construct(\App\Services\IpGeolocation $ipGeolocation)
    {
        $this->ipGeolocation = $ipGeolocation;
    }

    function index(Request $request, $ip = '')
    {
        $errorMessages = [];
        $ipInput = $request->input('ip',$ip);
        $userIP=$request->server('HTTP_CF_CONNECTING_IP')
            ?: $request->server('HTTP_X_REAL_IP')
            ?: $request->server('REMOTE_ADDR') ?: '';
        if ($ipInput) {
            $ipAddr=$ipInput;
            if (!filter_var($ipInput, FILTER_VALIDATE_IP)) {
                $ipAddr = gethostbyname($ipInput);
                if ($ipAddr == $ipInput) {
                    $errorMessages[] = 'Địa chỉ ip không đúng ' . $ipInput;
                }
            }
        }else{
            $ipAddr = $userIP;
        }

        $ipInfos = [];
        if (!$errorMessages) {
            $ipInfos = $this->ipGeolocation->getIpInfo($ipAddr);
            if (!$ipInfos) {
                $errorMessages[] = 'Không thể tìm thấy thông tin cho địa chỉ IP ' . $ipAddr;
            }
        }
        $infoLabels = [
            'ip' => 'Địa chỉ IP',
            'hostname' => 'Tên máy chủ',
            'continent' => 'Lục địa',
            'country' => 'Quốc gia',
            'regionName' => 'Vùng',
            'city' => 'Thành phố',
            'as' => 'Số hiệu mạng',
            'timezone' => 'Múi giờ',
            'residential' => 'Dân cư',
            'org' => 'Tổ chức',
            'isp' => 'Dịch vụ Internet',


        ];
        $ipInfoItems = [];
        foreach ($infoLabels as $key => $label) {
            foreach ($ipInfos as $provider => $ipInfo) {
                $infoValue = $ipInfo[$key] ?? '';
                if (is_array($infoValue)) {
                    $infoValue = implode(', ', $infoValue);
                }
                if($key=='residential'){
                    if($infoValue==='n/a') {
                        $infoValue = 'Không xác định';
                    }else {
                        $infoValue = $infoValue == 'yes' ? 'Có' : 'Không';
                    }
                }

                if ($infoValue) {
                    $ipInfoItems[$provider][$key] = ['label' => $label, 'value' => $infoValue];
                }
            }

        }
        return view('home', [
            'ipInfos' => $ipInfoItems,
            'errorMessages' => $errorMessages,
            'ip' => $ipInput,
            'countryCode' => $ipInfo['countryCode']??'',
        ]);

    }
}
