<?php
// app/Mail/RestaurantInviteMail.php
namespace App\Mail;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RestaurantInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User       $user,
        public Restaurant $restaurant
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You\'re invited to manage ' . $this->restaurant->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.restaurant-invite',
            with: [
                'inviteUrl'      => route('invite.accept', $this->user->invite_token),
                'restaurantName' => $this->restaurant->name,
                'ownerName'      => $this->user->name,
            ],
        );
    }
}