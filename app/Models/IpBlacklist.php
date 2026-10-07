<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpBlacklist extends Model
{
    protected $fillable = [
        'ip',
        'reason',
        'country_code',
    ];
}
