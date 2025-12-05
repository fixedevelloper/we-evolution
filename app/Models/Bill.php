<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',             // electricity, water, tv, internet, canal
        'account_number',
        'meter_reading_id',

        'previous_index',
        'current_index',
        'consumption',

        'amount',
        'currency',

        'description',
        'status',           // pending, awaiting_payment, paid, failed

        'operator',
        'payment_reference',
        'paid_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'meter_reading_id' => 'integer',
        'previous_index' => 'integer',
        'current_index' => 'integer',
        'consumption' => 'integer',
        'amount' => 'float',
        'paid_at' => 'datetime',
    ];

    /** Status constants */
    const STATUS_PENDING = 'pending';
    const STATUS_AWAITING_PAYMENT = 'awaiting_payment';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';

    /** Bill Types */
    const TYPE_ELECTRICITY = 'electricity';
    const TYPE_WATER = 'water';
    const TYPE_TV = 'tv';
    const TYPE_INTERNET = 'internet';
    const TYPE_CANAL_PLUS = 'canal';

    /** RELATIONS */
    public function user()
    {
       // return $this->belongsTo(User::class);
    }

    public function meterReading()
    {
        return $this->belongsTo(MeterReading::class);
    }

    /** Accessors */
    public function getIsPaidAttribute()
    {
        return $this->status === self::STATUS_PAID;
    }
}
