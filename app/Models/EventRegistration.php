<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'payment_status',
        'payment_reference',
        'amount_paid',
        'registered_at',
        'confirmed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'amount_paid' => 'integer',
    ];

    /**
     * رابطه با رویداد
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * رابطه با کاربر (ثبت‌نام‌کننده)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}