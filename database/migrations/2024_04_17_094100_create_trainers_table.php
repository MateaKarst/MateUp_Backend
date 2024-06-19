<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Run the migrations.
    public function up(): void
    // Create trainers table
    {
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('home_club_address');

            $table->string('expertise');
            $table->string('education')->nullable();
            $table->string('languages');
            $table->text('content_about');

            $table->string('rate_currency');
            $table->integer('rate_amount');
            $table->string('stripe_id');
            $table->string('stripe_url');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->nullable();
        });
    }

    // Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('trainer_profiles');
    }
};
