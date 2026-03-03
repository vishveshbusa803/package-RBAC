<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('authentication_settings', function (Blueprint $table) {
            $table->id('AuthSettingId');
            $table->string('AuthCode', 50);
            $table->string('AuthName', 100);
            $table->boolean('IsEnabled')->default(1);
            $table->integer('OTPAttempt')->nullable();
            $table->integer('OTPResetTime')->nullable();
            $table->integer('OTPExpiryTime')->nullable();
            $table->timestamp('CreatedOn')->useCurrent();
            $table->timestamp('UpdatedOn')->nullable()->useCurrent()->onUpdate(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('DeletionDate')->nullable();
            $table->boolean('IsActive')->nullable()->default(1);
            $table->integer('UserId')->nullable();
            $table->unique('AuthCode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authentication_settings');
    }
};
