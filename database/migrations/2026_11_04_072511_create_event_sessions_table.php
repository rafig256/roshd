<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('title', 255); // عنوان برنامه
            $table->string('time_string', 255); // زمان (مثلاً: روز اول، ساعت ۱۱)
            $table->string('location', 255)->nullable(); // محل
            $table->string('duration', 100)->nullable(); // طول زمان
            $table->text('description')->nullable(); // توضیحات
            $table->unsignedInteger('order')->default(0); // ترتیب
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_sessions');
    }
};
