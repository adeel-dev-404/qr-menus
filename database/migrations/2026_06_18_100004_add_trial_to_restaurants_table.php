<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            // When the free trial expires
            $table->timestamp('trial_ends_at')->nullable()->after('subscription_expires_at');
            // Which plan the trial is for
            $table->foreignId('trial_subscription_id')
                  ->nullable()
                  ->after('trial_ends_at')
                  ->constrained('subscriptions')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropForeign(['trial_subscription_id']);
            $table->dropColumn(['trial_ends_at', 'trial_subscription_id']);
        });
    }
};
