<?php


namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function export()
    {
        $clients = Client::withCount('reservations')->get();

        $filename = 'clients.csv';
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['Name', 'Email', 'Joined At', 'Reservations']);

        foreach ($clients as $client) {
            fputcsv($handle, [
                $client->name,
                $client->email,
                $client->created_at->format('Y-m-d'),
                $client->reservations_count,
            ]);
        }

        fclose($handle);

        return response()->download(file: $filename)->deleteFileAfterSend(true);
    }

    public function import(Request $request)
    {
        $file = $request->file('csv_file');
        if ($file) {
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));
    
            foreach ($data as $index => $row) {
                if ($index === 0) {
                    continue; // Skip header row
                }
    
                Client::updateOrCreate(
                    ['email' => $row[1]],
                    [
                        'name' => $row[0],
                        'email' => $row[1],
                        'created_at' => $row[2],
                        'username' => $row[3], // Assuming the username is in the 4th column
                        'phone_number' => $row[4], // Assuming the phone number is in the 5th column
                    ]
                );
            }
    
            return redirect()->back()->with('success', 'CSV file imported successfully.');
        }
    
        return redirect()->back()->with('error', 'Please upload a valid CSV file.');
    }

    public function show($id)
{
    $client = Client::findOrFail($id);
    return view('clients.show', compact('client'));
}
}
