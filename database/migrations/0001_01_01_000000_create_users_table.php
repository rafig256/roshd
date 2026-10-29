<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // شناسه ورود
            $table->string('national_code', 10)->unique();
            $table->string('password');

            // اطلاعات شخصی
            $table->string('name', 100);
            $table->string('family', 100);
            $table->string('mobile', 11)->unique();
            $table->string('email', 191)->nullable()->unique();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('avatar_path')->nullable();

            // وضعیت
            $table->enum('status', ['active', 'blocked'])->default('active');
            $table->boolean('is_innovator')->default(false);

            // تأییدها
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // ایندکس‌ها
            $table->index('is_innovator');
            $table->index('status');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};