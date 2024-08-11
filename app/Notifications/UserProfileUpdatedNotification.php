<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserProfileUpdatedNotification extends Notification
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail']; // or ['database'] if you want to store in the database
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Profile Updated')
            ->line('Your profile information has been updated.')
            ->line('Email: ' . $this->user->email)
            ->line('Phone Number: ' . $this->user->phone_number)
            ->action('View Profile', url('/profile'))
            ->line('Thank you for using our application!');
    }

    // If you want to store notifications in the database:
    public function toArray($notifiable)
    {
        return [
            'email' => $this->user->email,
            'phone_number' => $this->user->phone_number,
        ];
    }
}
