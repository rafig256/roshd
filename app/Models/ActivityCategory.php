<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityCategory extends Model
{
    public function innovatorRequests()
    {
        return $this->hasMany(InnovatorRequest::class);
    }
}
