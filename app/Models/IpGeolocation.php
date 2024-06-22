<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\IpGeolocation
 * @property int $id
 * @property string $ip
 * @property string $provider
 * @property string|null $hostname
 * @property string|null $continent
 * @property string|null $continentCode
 * @property string|null $country
 * @property string|null $countryCode
 * @property string|null $region
 * @property string|null $regionName
 * @property string|null $city
 * @property string|null $district
 * @property string|null $zip
 * @property float|null $lat
 * @property float|null $lon
 * @property string|null $timezone
 * @property int|null $offset
 * @property string|null $currency
 * @property string|null $isp
 * @property string|null $org
 * @property string|null $as
 * @property string|null $asname
 * @property bool|null $mobile
 * @property bool|null $proxy
 * @property bool|null $hosting
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 */
class IpGeolocation extends Model
{
    protected $guarded= ['id','created_at','updated_at'];
    protected $casts = [
        'lat' => 'float',
        'lon' => 'float',
        'mobile' => 'boolean',
        'proxy' => 'boolean',
        'hosting' => 'boolean',
    ];
    public static function purgeOldRecords($days=30)
    {
        return self::query()->where('created_at','<',now()->subDays($days))->delete();
    }
    protected function getResidentialAttribute()
    {
        if(is_null($this->mobile) && is_null($this->proxy) && is_null($this->hosting)){
            return 'n/a';
        }
        if($this->mobile || $this->proxy || $this->hosting){
            return 'no';
        }
        return 'yes';
    }
}
