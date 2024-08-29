<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',             // Type of notification (e.g., 'ReservationApproved', 'ReservationRejected')
        'notifiable_id',    // ID of the notifiable entity (e.g., User ID)
        'notifiable_type',  // Type of the notifiable entity (e.g., 'App\Models\User')
        'data',             // JSON data related to the notification (e.g., reservation details)
        'read_at',          // Timestamp when the notification was read
    ];

    /**
     * Get the notifiable entity that the notification belongs to.
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Check if the notification has been read.
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }
}
