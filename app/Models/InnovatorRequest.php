<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InnovatorRequest extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activityCategory()
    {
        return $this->belongsTo(ActivityCategory::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
