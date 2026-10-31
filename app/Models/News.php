<?php

namespace App\Models;

use App\Enums\NewsStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;

    protected $table = 'news';

    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'body',
        'cover_image_path',
        'author_id',
        'status',
        'published_at',
        'views_count',
        'is_featured',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'status' => NewsStatus::class,
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            NewsCategory::class,
            'news_news_category',
            'news_id',
            'news_category_id'
        );
    }
}
