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
}
