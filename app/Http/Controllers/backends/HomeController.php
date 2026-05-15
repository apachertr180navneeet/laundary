<?php

namespace App\Http\Controllers\backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function myprofile()
    {
        $user = Auth::user();
        if ($user->is_admin == '0') {
        }
        return view('backend.profile');
    }

    public function editprofile($id)
    {
        $user = Auth::user();
        if ($user->is_admin == '0') {
        }
        $user = User::find($id);
        return view('backend.updateProfile', compact('user'));
    }

    public function updateprofilepost(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::find($id);
        if (!$user) {
            return redirect()->route('myProfile')->with('error', 'User not found');
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);

            // Delete old image if exists
            if ($user->image && file_exists(public_path('images/' . $user->image))) {
                unlink(public_path('images/' . $user->image));
            }
            $user->image = $imageName;
        }

        $user->name = $request->input('name');
        $user->save();

        return redirect()->route('myProfile')->with('success', 'Profile updated successfully');
    }
}
