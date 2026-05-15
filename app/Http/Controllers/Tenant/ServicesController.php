<?php


namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Services;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ServicesController extends Controller
{
    public function index(Request $request)
    {
        // Initialize the query for the Services model
        $query = Services::query();

        // Check if the request is an AJAX call
        if ($request->ajax()) {
            // Retrieve the search input from the request
            $search = $request->input('search');

            // Apply search conditions if the search input is provided
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    // Search by Services name or mobile fields
                    $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('short_name', 'like', '%' . $search . '%');
                });
            }

            // Paginate the results and order by the latest entry
            $services = $query->orderBy('id', 'desc')->paginate(10);

            // Return a JSON response with the services and pagination links
            return response()->json([
                'services' => $services->items(),
                'pagination' => (string) $services->links(),
            ]);
        }

        // Handle non-AJAX requests: fetch services and render the view
        $services = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.service.service', compact('services'));
    }

    public function addServices(Request $request)
    {
        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'servicesname' => 'required|string|max:20|unique:services,name',
                'servicesshortname' => 'required|string|min:1|max:4',
            ], [
                'servicesname.required' => 'Please provide a services name.',
                'servicesname.string' => 'The services name should consist of valid text characters.',
                'servicesname.max' => 'The services name cannot exceed 20 characters.',
                'servicesname.unique' => 'This services name is already in use, please choose a different one.',

                'servicesshortname.required' => 'A short name for the services is required.',
                'servicesshortname.string' => 'The short name should be valid text.',
                'servicesshortname.min' => 'The short name must be at least 1 character.',
                'servicesshortname.max' => 'The short name cannot exceed 4 characters.',
            ]);

            // Redirect back with errors if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            // Handle the file upload for the services image
            $filename = null;
            if ($request->hasFile('servicesimage')) {
                $file = $request->file('servicesimage');
                // Generate a unique filename and store the file in the 'services' directory
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('services', $filename, 'public');
            }

            // Create a new services record in the database
            Services::create([
                'name' => $request->input('servicesname'),
                'short_name' => $request->input('servicesshortname'),
                'image' => $filename,
            ]);

            // Redirect to the services list with a success message
            return redirect()->route('services')->with('success', 'Services added successfully');
        } catch (\Throwable $throwable) {
            dd($throwable->getMessage());
            // Log any errors that occur during the process
            \Log::error($throwable->getMessage());
            // Redirect back with an error message
            return redirect()->back()->with('error', 'An error occurred while adding the Services.');
        }
    }

    public function deleteServices($id)
    {
        try {
            $services = Services::find($id);
            $services->delete();
            return response()->json(['message' => 'Services deleted successfully']);
        } catch (\Throwable $throwable) {
            dd($throwable->getMessage());
        }
    }

    public function edit($id)
    {
        $services = Services::findOrFail($id);
        return response()->json($services);
    }


    public function editServices(Request $request, $id)
    {
        // Custom validation messages
        $messages = [
            'servicesname.required' => 'The services name is required.',
            'servicesname.string' => 'The services name must be a string.',
            'servicesname.max' => 'The services name may not be greater than 255 characters.',
            'servicesname.unique' => 'The services name has already been taken.',
            'servicesshortname.required' => 'The short name is required.',
            'servicesshortname.string' => 'The short name must be a string.',
            'servicesshortname.max' => 'The short name may not be greater than 4 characters.',
            'servicesimage.image' => 'The file must be an image.',
            'servicesimage.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'servicesimage.max' => 'The image size may not be greater than 2MB.',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'servicesname' => 'required|string|max:255|unique:services,name,' . $id,
            'servicesshortname' => 'required|string|min:1|max:4',
            'servicesimage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        //dd($validator);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Find the services by ID
        $services = Services::findOrFail($id);

        // Update the services details
        $services->name = $request->servicesname;
        $services->short_name = $request->servicesshortname;

        // Check if a new image has been uploaded
        $filename = null;
        if ($request->hasFile('servicesimage')) {
            $file = $request->file('servicesimage');
            // Generate a unique filename and store the file in the 'services' directory
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('services', $filename, 'public');
            $services->image = $filename;
        }

        // Save the updated services
        $services->save();

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Services updated successfully'
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $query = Services::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('short_name', 'like', '%' . $search . '%');
            });
        }

        $services = $query->orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'services' => $services->items(),
            'pagination' => (string) $services->links(),
        ]);
    }

}
