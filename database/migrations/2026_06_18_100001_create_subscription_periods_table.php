<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'half_yearly', 'yearly']);
            $table->integer('duration_days');           // 30, 90, 180, 365
            $table->decimal('price', 10, 2);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['subscription_id', 'billing_cycle']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('subscription_periods');
    }
};
