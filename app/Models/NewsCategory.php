<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class NewsCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(NewsCategory::class, 'parent_id');
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'category_id');
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->slug) && !empty($model->name)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
