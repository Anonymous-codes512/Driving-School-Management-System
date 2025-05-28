<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Http\Controllers\Controller;


class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('pages.schoolowner.dashboard');
    }   
}
