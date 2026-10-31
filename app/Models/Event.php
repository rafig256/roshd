<?php

namespace App\Models;

use App\Enums\EventLocationType;
use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function speakers(): HasMany
    {
        return $this->hasMany(EventSpeaker::class, 'event_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class, 'event_id');
    }
}
