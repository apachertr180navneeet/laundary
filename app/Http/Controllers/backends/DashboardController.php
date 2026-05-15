<?php

namespace App\Http\Controllers\backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function dashboard()
    {
        try {
            $clientCounts = User::where('role_id', 2)->count();
            return view('backend.dashboard', compact('clientCounts'));
        } catch (\Throwable $e) {
            dd($e);
        }
    }
}
