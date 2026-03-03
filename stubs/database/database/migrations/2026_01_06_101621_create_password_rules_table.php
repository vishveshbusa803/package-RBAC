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
        Schema::create('password_rules', function (Blueprint $table) {
            $table->id('RuleId');
            $table->string('RuleCode', 50);
            $table->string('RuleName', 100);
            $table->boolean('IsEnabled')->default(1);
            $table->integer('RuleValue')->nullable();
            $table->timestamp('CreatedOn')->useCurrent();
            $table->timestamp('UpdatedOn')->nullable()->useCurrent()->onUpdate(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('IsActive')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_rules');
    }
};
