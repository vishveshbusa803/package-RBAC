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
        // Check if users table exists
        if (Schema::hasTable('users')) {
            // Table exists, modify it by adding address column
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'address')) {
                    $table->text('address')->nullable();
                }
            });
        } else {
            // Table doesn't exist, create it with all fields
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->text('address')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop the address column if table exists and column exists
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'address')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('address');
            });
        }
    }
};
