<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
