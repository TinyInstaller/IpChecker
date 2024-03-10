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

        $ipInfo = [];
        if (!$errorMessages) {
            $ipInfo = $this->ipGeolocation->getIpInfo($ipAddr);
            if (!$ipInfo) {
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
            'district' => 'Quận',
            'isp' => 'Dịch vụ Internet',
            'org' => 'Tổ chức',
            'as' => 'Đơn vị',
            'timezone' => 'Múi giờ',
        ];
        $ipInfoItems = [];
        foreach ($infoLabels as $key => $label) {
            $infoValue = $ipInfo[$key] ?? '';
            if (is_array($infoValue)) {
                $infoValue = implode(', ', $infoValue);
            }
            if ($infoValue) {
                $ipInfoItems[$key] = ['label' => $label, 'value' => $infoValue];
            }
        }
        return view('home', [
            'ipInfo' => $ipInfoItems,
            'errorMessages' => $errorMessages,
            'ip' => $ipInput,
            'countryCode' => $ipInfo['countryCode']??'',
        ]);

    }
}
