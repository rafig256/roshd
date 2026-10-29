<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 191)->unique();
            $table->string('title', 255);
            $table->text('excerpt')->nullable();
            $table->longText('body');
            $table->string('cover_image_path')->nullable();
            $table->foreignId('author_id')->constrained('users')->restrictOnDelete();

            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('published_at');
            $table->index('author_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};