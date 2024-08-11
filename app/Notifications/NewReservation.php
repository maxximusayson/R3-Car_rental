<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewReservation extends Notification implements ShouldQueue
{
    use Queueable;

    private $reservationData;

    public function __construct($reservationData)
    {
        $this->reservationData = $reservationData;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'user_name' => $this->reservationData['user_name'],
            'car' => $this->reservationData['car'],
            'start_date' => $this->reservationData['start_date'],
            'end_date' => $this->reservationData['end_date'],
        ];
    }
}
