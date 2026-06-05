<?php

namespace App\Mail;

use App\Models\RestaurantSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public RestaurantSubscription $subscription) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Subscription Activated — ' . $this->subscription->subscription->name . ' Plan',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.payment-approved');
    }
}