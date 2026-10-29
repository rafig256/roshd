<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 191)->unique();
            $table->string('name', 255);
            $table->foreignId('representative_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('plan_id')->unique()->constrained('plans')->restrictOnDelete();
            $table->foreignId('activity_category_id')->constrained('activity_categories')->restrictOnDelete();

            $table->enum('status', ['team', 'under_review', 'pending_company', 'company', 'inactive']);
            $table->text('description')->nullable();
            $table->string('logo_path')->nullable();

            // فیلدهای تیم
            $table->timestamp('team_approved_at')->nullable();
            $table->foreignId('team_approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('team_expires_at')->nullable();

            // فیلدهای حقوقی (شرکت)
            $table->string('legal_name', 255)->nullable();
            $table->string('national_id', 20)->nullable();
            $table->string('registration_number', 20)->nullable();
            $table->string('economic_code', 20)->nullable();
            $table->date('established_date')->nullable();
            $table->string('company_type', 50)->nullable();
            $table->text('legal_address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('email', 191)->nullable();

            // گردش کار شرکت
            $table->timestamp('company_requested_at')->nullable();
            $table->foreignId('company_approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('company_approved_at')->nullable();
            $table->text('company_rejection_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('representative_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};