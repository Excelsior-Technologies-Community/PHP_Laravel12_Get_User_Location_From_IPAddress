<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpHistory extends Model
{
    protected $fillable = [
        'ip',
        'country',
        'country_code',
        'region',
        'city',
        'zip',
        'latitude',
        'longitude',
        'isp',
        'asn',
        'timezone',
        'risk_score',
        'is_vpn_proxy',
        'is_blacklisted',
    ];

    protected $casts = [
        'is_vpn_proxy' => 'boolean',
        'is_blacklisted' => 'boolean',
    ];
}