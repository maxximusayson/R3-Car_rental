<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function setting()
    {
        // You can pass any data to the view here if needed
        return view('settings.setting');
    }
}
