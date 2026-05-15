<?php

namespace App\Http\Controllers\App;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        try {
            $users = User::get();
            //dd($tenants->toArray());
            return view('app.users.index',['users'=>$users]);
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('app.users.create');
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name'=>'required|string|max:255',
                'email'=>'required|email|max:255',
                'mobile_no' => 'required|string|max:20',
                'role' => 'required|in:user,admin',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create($validatedData);
            return redirect()->route('users.index');
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            //
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        try {
            //
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            //
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            //
        } catch (\Throwable $e) {
            dd($e);
        }
    }
}
