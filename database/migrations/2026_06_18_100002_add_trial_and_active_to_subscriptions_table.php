<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add trial_days and is_active to subscriptions; remove old price/duration
        // We keep old columns first to preserve data, then remove after migration
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedSmallInteger('trial_days')->default(0)->after('features');
            $table->boolean('is_active')->default(true)->after('trial_days');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['trial_days', 'is_active']);
        });
    }
};
