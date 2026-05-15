<?php

namespace App\Http\Controllers\App;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Company;
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
            return view('erp.users.index',['users'=>$users]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $companies = Company::where('status', 1)->get();
            $selectedCompany = $request->query('company_id');
            return view('erp.users.create', compact('companies', 'selectedCompany'));
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
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
                'company_id' => 'nullable|exists:companies,id',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create($validatedData);
            return redirect()->route('users.index');
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
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
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
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
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
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
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
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
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
        }
    }
}



