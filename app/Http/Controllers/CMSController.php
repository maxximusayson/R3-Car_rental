<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CMSController extends Controller
{
    public function index()
    {
        // Your CMS logic goes here
        return view('cms.index'); // Adjust the view name as per your folder structure
    }
}

