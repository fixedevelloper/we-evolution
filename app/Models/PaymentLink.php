<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PaymentLink extends Model
{
    protected $fillable = [
        'reference', 'user_id', 'amount', 'currency','email','name','phone',
        'description', 'status', 'expires_at',
        'redirect_url_success', 'redirect_url_failed'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
