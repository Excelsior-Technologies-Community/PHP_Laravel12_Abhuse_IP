<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpBlockingActivity extends Model
{
    protected $fillable = [
        'ip_address',
        'action',
        'reason',
    ];
}