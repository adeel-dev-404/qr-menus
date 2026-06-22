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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->boolean('ordering_allowed')->default(true)->after('ordering_enabled');
            $table->boolean('waiter_call_allowed')->default(true)->after('waiter_call_enabled');
            $table->boolean('deals_allowed')->default(true)->after('deals_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['ordering_allowed', 'waiter_call_allowed', 'deals_allowed']);
        });
    }
};
