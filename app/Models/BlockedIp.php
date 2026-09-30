<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedIp extends Model
{
    protected $fillable = [
        'ip_address',
        'reason',
        'status',
        'severity',
        'abuse_confidence_score',
        'country',
        'isp',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'abuse_confidence_score' => 'integer',
    ];

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at !== null
            && $this->expires_at->isPast();
    }

    public function getEffectiveStatusAttribute(): string
    {
        if ($this->is_expired) {
            return 'expired';
        }

        return $this->status;
    }
}