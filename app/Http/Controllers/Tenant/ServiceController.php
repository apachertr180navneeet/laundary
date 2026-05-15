<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Tenant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ServiceController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Service::query();

        if ($request->ajax()) {
            $search = $request->input('search');
            if (!empty($search)) {
                $query->where('name', 'like', '%' . $search . '%');
            }

            $services = $query->orderBy('id', 'desc')->paginate(10);

            return response()->json([
                'services' => $services->items(),
                'pagination' => (string) $services->links()
            ]);
        }

        $services = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.service', ['services' => $services]);
    }

    public function addService(Request $request)
    {
        // $validatedData = $request->validate([
        //     'name' => 'required|string|max:255',
        // ]);
        // Service::create($validatedData);
        // return redirect()->route('service');


        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:50',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            } else {

                $input = $request->all();
                Service::create([
                    'name' => $input['name'],
                ]);
                // dd($client);
                return redirect()->route('service')->with('success', 'Service added successfully');
            }
        } catch (\Throwable $throwable) {
            dd($throwable->getMessage());
        }
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        // You can pass $service to the view for editing
        return view('admin.service', ['services' => $service]);
    }

    public function updateService(Request $request, $id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->name = $request->input('name');
            $service->save();

            return redirect()->back()->with('success', 'Service updated successfully');
        } catch (\Throwable $throwable) {
            dd($throwable->getMessage());
        }
    }


    public function deleteService($id)
    {
        try {
            $resource = Service::findOrFail($id);
            $resource->delete();

            return response()->json(['message' => 'Resource deleted successfully']);
        } catch (\Throwable $throwable) {
            dd($throwable->getMessage());
        }
    }
}
