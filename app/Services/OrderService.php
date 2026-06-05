<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewOrderMail;

class OrderService
{
    public function placeOrder(Restaurant $restaurant, array $data, array $cartItems): Order
    {
        return DB::transaction(function () use ($restaurant, $data, $cartItems) {

            // Calculate totals
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            // Create order
            $order = Order::create([
                'restaurant_id'    => $restaurant->id,
                'branch_id'        => $data['branch_id'] ?? null,
                'table_id'         => $data['table_id'] ?? null,
                'customer_name'    => $data['customer_name'],
                'customer_phone'   => $data['customer_phone'],
                'customer_address' => $data['customer_address'] ?? null,
                'type'             => $data['type'],
                'payment_method'   => $data['payment_method'],
                'payment_status'   => 'pending',
                'status'           => 'pending',
                'subtotal'         => $subtotal,
                'total'            => $subtotal,
                'notes'            => $data['notes'] ?? null,
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'          => $order->id,
                    'product_id'        => $item['product_id'],
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'product_name'      => $item['name'],
                    'variant_name'      => $item['variant_name'] ?? null,
                    'price'             => $item['price'],
                    'quantity'          => $item['quantity'],
                    'subtotal'          => $item['price'] * $item['quantity'],
                ]);
            }

            // Send WhatsApp notification
            $this->sendWhatsAppNotification($restaurant, $order);
            $ownerEmail = $order->restaurant->users()
                ->whereHas('roles', fn($q) => $q->where('name', 'restaurant_owner'))
                ->value('email');

            if ($ownerEmail) {
                Mail::to($ownerEmail)->queue(new NewOrderMail($order->load('items', 'table', 'branch')));
            }
            return $order;
        });
    }

    // public function updateStatus(Order $order, string $status): void
    // {
    //     $updates = ['status' => $status];

    //     if ($status === 'confirmed') $updates['confirmed_at'] = now();
    //     if ($status === 'ready')     $updates['ready_at']     = now();

    //     $order->update($updates);
    // }
    public function updateStatus(Order $order, string $status): void
    {
        $updates = ['status' => $status];
        if ($status === 'confirmed') $updates['confirmed_at'] = now();
        if ($status === 'ready')     $updates['ready_at']     = now();
        $order->update($updates);
        $order->refresh();

        // Send customer email if available
        if ($order->customer_email && in_array($status, ['confirmed', 'preparing', 'ready', 'delivered', 'cancelled'])) {
            Mail::to($order->customer_email)
                ->queue(new OrderStatusUpdatedMail($order->load('items'), $status));
        }
    }
    public function confirmPayment(Order $order, string $reference, ?string $proofPath = null): void
    {
        $order->update([
            'payment_status'    => 'paid',
            'payment_reference' => $reference,
            'payment_proof'     => $proofPath,
        ]);
    }

    private function sendWhatsAppNotification(Restaurant $restaurant, Order $order): void
    {
        if (empty($restaurant->whatsapp_number)) return;

        $phone   = preg_replace('/[^0-9]/', '', $restaurant->whatsapp_number);
        if (str_starts_with($phone, '0')) $phone = '92' . substr($phone, 1);

        $items = $order->items->map(
            fn($i) =>
            "• {$i->product_name}" .
                ($i->variant_name ? " ({$i->variant_name})" : '') .
                " x{$i->quantity} = Rs." . number_format($i->subtotal, 0)
        )->join("\n");

        $type    = $order->type === 'dine_in' ? '🍽 Dine-in' : '🥡 Takeaway';
        $payment = match ($order->payment_method) {
            'jazzcash'  => '💚 JazzCash',
            'easypaisa' => '💙 Easypaisa',
            default     => '💵 Pay Later',
        };

        $message = urlencode(
            "🔔 *NEW ORDER — {$order->order_number}*\n\n" .
                "*{$type}*" . ($order->table ? " | Table {$order->table->table_number}" : '') . "\n\n" .
                "*Items:*\n{$items}\n\n" .
                "*Total: Rs." . number_format($order->total, 0) . "*\n" .
                "*Payment: {$payment}*\n\n" .
                "*Customer:* {$order->customer_name}\n" .
                "*Phone:* {$order->customer_phone}\n" .
                ($order->notes ? "*Notes:* {$order->notes}" : '')
        );

        // Opens WhatsApp with pre-filled message
        // In production, use WhatsApp Business API for auto-sending
        // For now store the URL so dashboard can show "Send WhatsApp" button
        $order->update([
            'notes' => $order->notes . "\n[WA:" . "https://wa.me/{$phone}?text={$message}]",
        ]);
    }
}
