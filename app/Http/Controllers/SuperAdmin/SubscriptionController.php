<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function subscription()
    {
        return view('pages.superadmin.subscription');
    }

    public function subscriptionRequests()
    {
        return view('pages.superadmin.subscription_request');
    }
}
