<?php

namespace App\Models;

use App\Enums\OrganizationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    protected $table = 'organizations';

    protected $fillable = [
        'slug',
        'name',
        'representative_id',
        'plan_id',
        'activity_category_id',
        'status',
        'description',
        'logo_path',
        'team_approved_at',
        'team_approved_by',
        'team_expires_at',
        'legal_name',
        'national_id',
        'registration_number',
        'economic_code',
        'established_date',
        'company_type',
        'legal_address',
        'phone',
        'website',
        'email',
        'company_requested_at',
        'company_approved_by',
        'company_approved_at',
        'company_rejection_reason',
    ];

    protected $casts = [
        'status' => OrganizationStatus::class,
        'team_approved_at' => 'datetime',
        'team_expires_at' => 'date',
        'established_date' => 'date',
        'company_requested_at' => 'datetime',
        'company_approved_at' => 'datetime',
    ];

    public function representative(): BelongsTo
    {
        return $this->belongsTo(User::class, 'representative_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function activityCategory(): BelongsTo
    {
        return $this->belongsTo(ActivityCategory::class, 'activity_category_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(OrganizationMember::class, 'organization_id');
    }

    public function joinRequests(): HasMany
    {
        return $this->hasMany(OrganizationJoinRequest::class, 'organization_id');
    }
}
