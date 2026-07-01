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
        'longitude'
    ];
}