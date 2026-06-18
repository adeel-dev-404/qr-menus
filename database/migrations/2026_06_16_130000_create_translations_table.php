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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->morphs('translatable');     // translatable_type + translatable_id
            $table->string('locale', 10);       // 'ur', 'ar', 'tr', etc.
            $table->string('field');            // 'name', 'description', etc.
            $table->text('value');
            $table->timestamps();

            // Each model+locale+field combo must be unique
            $table->unique(
                ['translatable_type', 'translatable_id', 'locale', 'field'],
                'trans_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
