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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();   // e.g. ORD-20240515-001
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('table_id')->nullable()->constrained()->nullOnDelete();

            // Customer info
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_address')->nullable(); // for takeaway delivery

            // Order type
            $table->enum('type', ['dine_in', 'takeaway'])->default('dine_in');

            // Payment
            $table->enum('payment_method', ['jazzcash', 'easypaisa', 'pay_later'])->default('pay_later');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('payment_reference')->nullable();  // transaction ID
            $table->string('payment_proof')->nullable();       // screenshot path

            // Order status
            $table->enum('status', [
                'pending',      // just placed
                'confirmed',    // restaurant confirmed
                'preparing',    // kitchen is making it
                'ready',        // ready for pickup/serving
                'delivered',    // delivered/served
                'cancelled',    // cancelled
            ])->default('pending');

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('notes')->nullable();              // customer notes
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
