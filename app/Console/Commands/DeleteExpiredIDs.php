<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Illuminate\Support\Facades\Storage;

class DeleteExpiredIDs extends Command
{
    protected $signature = 'ids:delete-expired';

    protected $description = 'Delete expired uploaded IDs from the server and database';

    public function handle()
    {
        // Get current time
        $now = now();

        // Fetch reservations where expiration_time is less than the current time
        $expiredReservations = Reservation::where('expiration_time', '<', $now)->get();

        foreach ($expiredReservations as $reservation) {
            // Delete files from server
            if (Storage::exists($reservation->driver_license)) {
                Storage::delete($reservation->driver_license);
            }
            if (Storage::exists($reservation->valid_id)) {
                Storage::delete($reservation->valid_id);
            }

            // Optionally, you can remove the files from the database
            $reservation->driver_license = null;
            $reservation->valid_id = null;
            $reservation->expiration_time = null; // Optional if you want to clear the expiration time
            $reservation->save();
        }

        $this->info('Expired IDs have been deleted successfully.');
    }
}
