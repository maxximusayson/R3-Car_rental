<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ContactFormNotification extends Notification
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Contact Form Submission')
                    ->line('You have received a new contact form submission.')
                    ->line('First Name: ' . $this->data['first_name'])
                    ->line('Last Name: ' . $this->data['last_name'])
                    ->line('Email: ' . $this->data['email'])
                    ->line('Phone: ' . $this->data['phone'])
                    ->line('Subject: ' . $this->data['subject'])
                    ->line('Message: ' . $this->data['message']);
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->data['message'],
            'subject' => $this->data['subject'],
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
