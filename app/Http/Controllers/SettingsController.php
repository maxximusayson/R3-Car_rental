<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AddNewAdminController;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function setting()
    {
        return view('settings.setting');
    }

    /**
     * Handle the form submission to add a new admin by using AddNewAdminController.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addAdmin(Request $request)
    {
        // Instantiate AddNewAdminController and call its register method
        $adminController = new AddNewAdminController();
        return $adminController->register($request);
    }
}
