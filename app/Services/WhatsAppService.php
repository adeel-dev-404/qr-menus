
<?php

namespace App\Services;

use App\Models\Order;

class WhatsAppService
{
    public static function sendOrderNotification(Order $order): string
    {
        $restaurant = $order->restaurant;
        $phone      = $restaurant->whatsapp_number;

        if (!$phone) return '';

        // Format phone: remove 0, add 92
        $phone = '92' . ltrim(preg_replace('/[^0-9]/', '', $phone), '0');

        $items = $order->items->map(
            fn($i) =>
            "• {$i->product_name}" .
                ($i->variant_name ? " ({$i->variant_name})" : '') .
                " x{$i->quantity} = Rs. " . number_format($i->subtotal, 0)
        )->implode("\n");

        $type    = $order->order_type === 'dine_in'
            ? "🍽 Dine-in" . ($order->table_number ? " — Table {$order->table_number}" : '')
            : "🥡 Takeaway";

        $payment = match ($order->payment_method) {
            'jazzcash'  => "JazzCash",
            'easypaisa' => "Easypaisa",
            default     => "Cash",
        };

        $message = "🔔 *New Order — {$order->order_number}*\n\n" .
            "*Type:* {$type}\n" .
            "*Customer:* {$order->customer_name}\n" .
            "*Phone:* {$order->customer_phone}\n\n" .
            "*Items:*\n{$items}\n\n" .
            "*Total:* Rs. " . number_format($order->total, 0) . "\n" .
            "*Payment:* {$payment} — " . ucfirst($order->payment_status) . "\n" .
            ($order->notes ? "*Notes:* {$order->notes}\n" : '') .
            "\nView in dashboard: " . url('/dashboard/orders');

        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }
}
