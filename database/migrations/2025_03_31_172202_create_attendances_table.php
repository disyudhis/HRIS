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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade')->nullable();
            $table->dateTime('checked_time')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('distance')->comment('Distance in meters from office')->nullable();
            $table->enum('status', ['PRESENT', 'LATE', 'ABSENT', 'MISSED'])->default('PRESENT');
            $table->text('notes')->nullable();
            $table->enum('type', ['CHECK_IN', 'CHECK_OUT'])->nullable();
            $table->boolean('is_checked')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
