<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Middleware;

class AdminDashboardController extends Controller
{
    // Ensure only authenticated admins can access the dashboard
    public function __construct()
    {
       
    }

    // Render the dashboard view
    public function index()
    {
      $admin = Auth::guard('admin')->user(); // Retrieve authenticated admin details
      return view('admin.dashboard', compact('admin')); // Pass admin details to the view
    }
}
