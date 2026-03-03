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
        Schema::create('email_login_otp', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id')->nullable();
            $table->string('email', 256)->nullable();
            $table->string('otp_type', 20);
            $table->string('otp_hash', 256);
            $table->dateTime('expiry_time');
            $table->boolean('is_used')->default(0);
            $table->timestamps();
            $table->dateTime('used_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_login_otp');
    }
};
