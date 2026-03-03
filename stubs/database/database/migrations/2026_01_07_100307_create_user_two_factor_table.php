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
        Schema::create('user_two_factor', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('secret_key', 100)->nullable();
            $table->boolean('is_enabled')->default(0);
            $table->boolean('is_active')->nullable();
            $table->boolean('is_setup_completed')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_two_factor');
    }
};
