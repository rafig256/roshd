<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'attended'])->default('confirmed');
            $table->enum('payment_status', ['not_required', 'pending', 'paid', 'refunded', 'failed'])->default('not_required');
            $table->string('payment_reference', 255)->nullable();
            $table->unsignedBigInteger('amount_paid')->nullable();

            $table->timestamp('registered_at');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'user_id']);
            $table->index(['event_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};