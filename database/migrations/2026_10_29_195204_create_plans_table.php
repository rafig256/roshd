<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 191)->unique();
            $table->foreignId('innovator_id')->constrained('users')->cascadeOnDelete();
            $table->string('title', 255);
            $table->foreignId('activity_category_id')->constrained('activity_categories')->restrictOnDelete();
            $table->unsignedInteger('estimated_duration_months')->nullable();
            $table->unsignedBigInteger('estimated_budget')->nullable();
            $table->longText('description');

            $table->enum('status', ['pending', 'approved', 'rejected', 'archived'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['innovator_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};