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
            $table->string('cover_image')->nullable()->after('logo');
            $table->text('about')->nullable()->after('cover_image');
            $table->string('whatsapp')->nullable()->after('about');
            $table->string('instagram')->nullable()->after('whatsapp');
            $table->string('facebook')->nullable()->after('instagram');
            $table->json('opening_hours')->nullable()->after('facebook');
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
