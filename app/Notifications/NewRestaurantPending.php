
<?php

// namespace App\Notifications;

use App\Models\Restaurant;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewRestaurantPending extends Notification
{
    public function __construct(public Restaurant $restaurant) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Restaurant Pending Approval — ' . $this->restaurant->name)
            ->greeting('Hello Admin,')
            ->line('A new restaurant has registered and is waiting for approval.')
            ->line('Restaurant: **' . $this->restaurant->name . '**')
            ->action('Review & Approve', url('/admin/restaurants'))
            ->line('Please log into the admin panel to approve or reject.');
    }
}