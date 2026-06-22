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
            $table->boolean('categories_allowed')->default(true)->after('deals_allowed');
            $table->boolean('products_allowed')->default(true)->after('categories_allowed');
            $table->boolean('qr_codes_allowed')->default(true)->after('products_allowed');
            $table->boolean('branches_allowed')->default(true)->after('qr_codes_allowed');
            $table->boolean('staff_allowed')->default(true)->after('branches_allowed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn([
                'categories_allowed',
                'products_allowed',
                'qr_codes_allowed',
                'branches_allowed',
                'staff_allowed'
            ]);
        });
    }
};
