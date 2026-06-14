<?php

namespace App\Mail;

use App\Models\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RestaurantApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Restaurant $restaurant) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Your Restaurant is Now Active — ' . $this->restaurant->name,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.restaurant-approved');
    }
}
