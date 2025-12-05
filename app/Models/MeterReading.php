<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeterReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',                 // electricity, water
        'meter_number',
        'previous_index',
        'current_index',
        'raw_text',
        'status',               // pending, validated, failed
    ];

    protected $casts = [
        'previous_index' => 'integer',
        'current_index' => 'integer',
        'user_id' => 'integer',
    ];

    /** Status constants */
    const STATUS_PENDING = 'pending';
    const STATUS_VALIDATED = 'validated';
    const STATUS_FAILED = 'failed';

    /** TYPE constantes */
    const TYPE_ELECTRICITY = 'electricity';
    const TYPE_WATER = 'water';

    /** RELATIONS */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    /** Accessors */
    public function getConsumptionAttribute()
    {
        if ($this->previous_index && $this->current_index) {
            return $this->current_index - $this->previous_index;
        }
        return null;
    }
}
