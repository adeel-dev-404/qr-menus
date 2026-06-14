<?php

namespace App\Mail;

use App\Models\RestaurantSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentRequestSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public RestaurantSubscription $subscription) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '💳 New Payment Request — ' . ($this->subscription->restaurant->name ?? 'Restaurant') . ' · Rs. ' . number_format($this->subscription->amount_paid, 0),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.payment-request-submitted');
    }
}
