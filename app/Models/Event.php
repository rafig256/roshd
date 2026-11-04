<?php

namespace App\Models;

use App\Enums\EventLocationType;
use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Event extends Model
{
    use SoftDeletes;

    protected $table = 'events';

    protected $fillable = [
        'slug',
        'title',
        'description',
        'cover_image_path',
        'starts_at',
        'ends_at',
        'location_type',
        'location_address',
        'location_link',
        'platform',
        'capacity',
        'registration_required',
        'requires_payment',
        'price_amount',
        'price_currency',
        'registration_deadline',
        'status',
        'views_count',
        'created_by',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'location_type' => EventLocationType::class,
        'capacity' => 'integer',
        'registration_required' => 'boolean',
        'requires_payment' => 'boolean',
        'price_amount' => 'integer',
        'registration_deadline' => 'datetime',
        'status' => EventStatus::class,
        'views_count' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            EventCategory::class,
            'event_event_category',
            'event_id',
            'event_category_id'
        );
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(EventSession::class, 'event_id');
    }

    public function speakers(): HasMany
    {
        return $this->hasMany(EventSpeaker::class, 'event_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class, 'event_id');
    }

    protected function displayStatus(): Attribute
    {
        return Attribute::get(function () {
            $status = $this->status instanceof \BackedEnum ? $this->status->value : $this->status;

            if ($status === 'cancelled') return 'cancelled';
            if ($status === 'finished' || ($this->ends_at && $this->ends_at->isPast())) return 'finished';
            if (!$this->registration_required) return 'open';
            if ($this->registration_deadline && $this->registration_deadline->isPast()) return 'closed';

            return 'registering';
        });
    }

        // این متد تاریخ شمسی آماده را برمی‌گرداند
    public function getJalaliDateAttribute()
        {
            if (!$this->starts_at) return null;
            
            $date = \Morilog\Jalali\Jalalian::fromCarbon($this->starts_at);
            $formattedDate = $date->format('j F Y');
            
            // تبدیل اعداد به فارسی
            $formattedDate = strtr($formattedDate, ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹']);
            $time = strtr($this->starts_at->format('H:i'), ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹']);
            
            return $formattedDate . ' - ساعت ' . $time;
        }

            // بررسی اینکه آیا رویداد در حال ثبت‌نام است
    public function isRegistering()
    {
        // اگر ثبت‌نام نیاز نباشد، همیشه باز است
        if (!$this->registration_required) {
            return true; 
        }

        // اگر نیاز به ثبت‌نام باشد، چک می‌کنیم که مهلت ثبت‌نام نگذشته باشد
        if ($this->registration_deadline && now()->greaterThan($this->registration_deadline)) {
            return false;
        }

        // بررسی می‌کنیم که ظرفیت پر نشده باشد (اگر ظرفیت تعریف شده باشد)
        if ($this->capacity && $this->registrations()->count() >= $this->capacity) {
            return false;
        }

        return true;
    }
}
