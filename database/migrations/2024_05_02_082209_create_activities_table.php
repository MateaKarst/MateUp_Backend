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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('member_id');
            $table->string('workout_type');
            $table->string('location');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('description');
            $table->integer('repeats')->nullable();
            $table->enum('recurrence', ['none', 'daily', 'weekly', 'monthly'])->default('none'); // Column for recurrence
            $table->date('reminder_date')->nullable();
            $table->time('reminder_time')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
