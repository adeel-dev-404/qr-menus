<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order, public string $status) {}

    public function envelope(): Envelope
    {
        $subjects = [
            'confirmed' => '✅ Order Confirmed — ' . $this->order->order_number,
            'preparing' => '👨‍🍳 Your Order is Being Prepared — ' . $this->order->order_number,
            'ready'     => '🔔 Your Order is Ready! — ' . $this->order->order_number,
            'delivered' => '🎉 Order Delivered — ' . $this->order->order_number,
            'cancelled' => '❌ Order Cancelled — ' . $this->order->order_number,
        ];

        return new Envelope(
            subject: $subjects[$this->status] ?? 'Order Update — ' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.order-status-updated');
    }
}
