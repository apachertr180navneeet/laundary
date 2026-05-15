<?php

namespace App\Http\Controllers\backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function dashboard()
    {
        $clientCounts = User::where('is_deleted', 0)->where('role_id', 2)->count();
        return view('backend.dashboard', compact('clientCounts'));
    }

}
