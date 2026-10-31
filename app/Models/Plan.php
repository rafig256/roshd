<?php

namespace App\Models;

use App\Enums\PlanStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $table = 'plans';

    protected $fillable = [
        'slug',
        'innovator_id',
        'title',
        'activity_category_id',
        'estimated_duration_months',
        'estimated_budget',
        'description',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'estimated_duration_months' => 'integer',
        'estimated_budget' => 'integer',
        'status' => PlanStatus::class,
        'reviewed_at' => 'datetime',
    ];

    public function innovator(): BelongsTo
    {
        return $this->belongsTo(Innovator::class, 'innovator_id');
    }

    public function activityCategory(): BelongsTo
    {
        return $this->belongsTo(ActivityCategory::class, 'activity_category_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class, 'plan_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PlanAttachment::class, 'plan_id');
    }
}
