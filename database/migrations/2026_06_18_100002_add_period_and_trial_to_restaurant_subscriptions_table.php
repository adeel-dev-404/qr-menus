<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_subscriptions', function (Blueprint $table) {
            // Link to specific billing period chosen by the restaurant
            $table->foreignId('subscription_period_id')
                  ->nullable()
                  ->after('subscription_id')
                  ->constrained('subscription_periods')
                  ->nullOnDelete();

            // Whether this record represents a free trial (not a paid subscription)
            $table->boolean('is_trial')->default(false)->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_subscriptions', function (Blueprint $table) {
            $table->dropForeign(['subscription_period_id']);
            $table->dropColumn(['subscription_period_id', 'is_trial']);
        });
    }
};
