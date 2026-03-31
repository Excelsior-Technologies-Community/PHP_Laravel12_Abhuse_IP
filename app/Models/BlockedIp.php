<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedIp extends Model
{
    // આ લાઈન ઉમેરવી જરૂરી છે
    protected $fillable = ['ip_address', 'reason'];
}