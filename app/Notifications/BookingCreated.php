<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Reservation;

class BookingCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reservation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A new booking has been created.')
                    ->line('User: ' . $this->reservation->user->name)
                    ->line('Car: ' . $this->reservation->car->brand . ' ' . $this->reservation->car->model)
                    ->line('Start Date: ' . $this->reservation->start_date)
                    ->line('End Date: ' . $this->reservation->end_date)
                    ->action('View Reservation', url('/admin/reservations/' . $this->reservation->id))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'reservation_id' => $this->reservation->id,
            'user' => $this->reservation->user->name,
            'car' => $this->reservation->car->brand . ' ' . $this->reservation->car->model,
            'start_date' => $this->reservation->start_date,
            'end_date' => $this->reservation->end_date,
        ];
    }
}
