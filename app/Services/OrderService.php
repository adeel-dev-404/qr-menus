<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function placeOrder(array $data, array $items, Restaurant $restaurant): Order
    {
        return DB::transaction(function () use ($data, $items, $restaurant) {

            // Calculate totals
            $subtotal = collect($items)->sum(fn($i) => $i['unit_price'] * $i['quantity']);

            $order = Order::create([
                'order_number'   => Order::generateNumber($restaurant->id),
                'restaurant_id'  => $restaurant->id,
                'branch_id'      => $data['branch_id'] ?? null,
                'table_id'       => $data['table_id'] ?? null,
                'customer_name'  => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'customer_email' => $data['customer_email'] ?? null,
                'type'           => $data['type'],
                'status'         => 'pending',
                'payment_method' => $data['payment_method'],
                'payment_status' => $data['payment_method'] === 'cash' ? 'unpaid' : 'pending',
                'subtotal'       => $subtotal,
                'discount'       => 0,
                'total'          => $subtotal,
                'customer_notes' => $data['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'          => $order->id,
                    'product_id'        => $item['product_id'],
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'product_name'      => $item['product_name'],
                    'variant_name'      => $item['variant_name'] ?? null,
                    'unit_price'        => $item['unit_price'],
                    'quantity'          => $item['quantity'],
                    'subtotal'          => $item['unit_price'] * $item['quantity'],
                    'notes'             => $item['notes'] ?? null,
                ]);
            }

            return $order->load('items');
        });
    }

    public function updateStatus(Order $order, string $status): void
    {
        $order->update(['status' => $status]);
    }

    public function confirmPayment(Order $order, string $reference, ?string $proofPath = null): void
    {
        $order->update([
            'payment_status'    => 'paid',
            'payment_reference' => $reference,
            'payment_proof'     => $proofPath,
        ]);
    }

    // Build WhatsApp message for order notification
    public function buildWhatsAppMessage(Order $order): string
    {
        $items = $order->items->map(
            fn($i) =>
            "• {$i->product_name}" .
                ($i->variant_name ? " ({$i->variant_name})" : '') .
                " x{$i->quantity} = Rs. " . number_format($i->subtotal, 0)
        )->join("\n");

        $type  = $order->type === 'dine_in' ? '🍽 Dine-in' : '🥡 Takeaway';
        $table = $order->table_id ? " — Table {$order->table->table_number}" : '';

        return urlencode(
            "🔔 *New Order — {$order->order_number}*\n\n" .
                "*Type:* {$type}{$table}\n" .
                "*Customer:* {$order->customer_name}\n" .
                "*Phone:* {$order->customer_phone}\n\n" .
                "*Items:*\n{$items}\n\n" .
                "*Total:* Rs. " . number_format($order->total, 0) . "\n" .
                "*Payment:* " . ucfirst($order->payment_method) . "\n" .
                ($order->customer_notes ? "*Notes:* {$order->customer_notes}" : '')
        );
    }
}
