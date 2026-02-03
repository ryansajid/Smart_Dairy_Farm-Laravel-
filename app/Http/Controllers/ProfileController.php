<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function index()
    {
        // return "This is the Profile page ";
        return view('auth_custom.profile');
    }
}
