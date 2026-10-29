<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 191)->unique();
            $table->string('title', 255);
            $table->longText('description');
            $table->string('cover_image_path')->nullable();

            $table->timestamp('starts_at');
            $table->timestamp('ends_at');

            $table->enum('location_type', ['physical', 'online', 'hybrid']);
            $table->text('location_address')->nullable();
            $table->string('location_link', 500)->nullable();
            $table->string('platform', 50)->nullable();

            $table->unsignedInteger('capacity')->nullable();
            $table->boolean('registration_required')->default(false);
            $table->boolean('requires_payment')->default(false);
            $table->unsignedBigInteger('price_amount')->nullable();
            $table->string('price_currency', 10)->default('IRR');
            $table->timestamp('registration_deadline')->nullable();

            $table->enum('status', ['draft', 'published', 'cancelled', 'finished'])->default('draft');
            $table->unsignedBigInteger('views_count')->default(0);
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('starts_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};