<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::where('is_deleted', 0)->get();
        return view('tenants.index', ['tenants' => $tenants]);
    }

    public function create()
    {
        return view('tenants.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tenants,email',
            'password' => ['required', Rules\Password::defaults()],
        ]);

        Tenant::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ]);

        return redirect()->route('tenants.index');
    }

    public function show(Tenant $tenant)
    {
        //
    }

    public function edit(Tenant $tenant)
    {
        $tenant_data = Tenant::find($tenant->id);
        return view('tenants.edit', ['tenents' => $tenant_data]);
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $tenant->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->active,
        ]);

        return redirect()->route('tenants.index');
    }

    public function destroy(Tenant $tenant)
    {
        //
    }

    public function deleteTenant($id)
    {
        try {
            DB::table('tenants')->where('id', '=', $id)->update(['is_deleted' => 1]);
            return response()->json(['message' => 'Resource deleted successfully']);
        } catch (\Throwable $throwable) {
            return response()->json(['error' => $throwable->getMessage()], 500);
        }
    }
}
