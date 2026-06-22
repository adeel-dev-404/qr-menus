<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('account_title');
            $table->string('account_number');
            $table->text('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('restaurant_subscriptions', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->onDelete('set null');
        });

        // Seed with the default hardcoded payment method
        DB::table('payment_methods')->insert([
            'name' => 'Easypaisa / JazzCash',
            'account_title' => 'Adeel Ahmed',
            'account_number' => '03448371946',
            'instructions' => 'Transfer the exact amount above and keep your transaction receipt handy.',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('restaurant_subscriptions', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn('payment_method_id');
        });

        Schema::dropIfExists('payment_methods');
    }
};
