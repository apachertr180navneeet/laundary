<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class CompanyController extends Controller
{
    public function index()
    {
        try {
            $companies = Company::withCount('users')->orderBy('id', 'desc')->paginate(10);
            return view('erp.company.index', compact('companies'));
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'gst_number' => 'nullable|string|max:50',
                'status' => 'nullable|boolean',
            ]);

            $data = $request->all();
            $data['slug'] = Str::slug($request->name);
            Company::create($data);

            return redirect()->route('company.index')->with('success', 'Company added successfully');
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $company = Company::findOrFail($id);
            return response()->json($company);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'gst_number' => 'nullable|string|max:50',
                'status' => 'nullable|boolean',
            ]);

            $company = Company::findOrFail($id);
            $data = $request->all();
            $data['slug'] = Str::slug($request->name);
            $company->update($data);

            return redirect()->route('company.index')->with('success', 'Company updated successfully');
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function users($id)
    {
        try {
            $company = Company::findOrFail($id);
            $users = User::where('company_id', $id)->orderBy('id', 'desc')->paginate(10);
            return view('erp.company.users', compact('company', 'users'));
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $company = Company::findOrFail($id);
            $company->delete();

            return response()->json(['success' => true, 'message' => 'Company deleted successfully']);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
