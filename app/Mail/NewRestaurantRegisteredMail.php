<?php

namespace App\Mail;

use App\Models\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewRestaurantRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Restaurant $restaurant) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🏠 New Restaurant Pending Approval — ' . $this->restaurant->name,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.new-restaurant-registered');
    }
}