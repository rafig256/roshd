<?php

namespace App\Models;

use App\Enums\JoinRequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationJoinRequest extends Model
{
    protected $table = 'organization_join_requests';

    protected $fillable = [
        'organization_id',
        'user_id',
        'message',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'created_member_id',
    ];

    protected $casts = [
        'status' => JoinRequestStatus::class,
        'reviewed_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
