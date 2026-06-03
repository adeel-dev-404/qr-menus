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
            $table->boolean('ordering_enabled')->default(false)->after('status');
            $table->string('jazzcash_number')->nullable()->after('ordering_enabled');
            $table->string('easypaisa_number')->nullable()->after('jazzcash_number');
            $table->string('whatsapp_number')->nullable()->after('easypaisa_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            //
        });
    }
};
