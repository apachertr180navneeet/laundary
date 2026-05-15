<?php


namespace App\Http\Controllers\Modules;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\User;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Category::query();

        if ($request->ajax()) {
            $search = $request->input('search');

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('short_name', 'like', '%' . $search . '%');
                });
            }

            // Paginate results
            $categories = $query->orderBy('id', 'desc')->paginate(10);

            // Return data for AJAX
            return response()->json([
                'categories' => $categories->items(),
                'pagination' => $categories->links('pagination::bootstrap-4')->toHtml() // Ensure pagination HTML is being returned correctly
            ]);
        }

        // Handle non-AJAX request
        $categories = $query->orderBy('id', 'desc')->paginate(10);
        return view('erp.category.category', compact('categories'));
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function addCategory(Request $request)
    {
        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'categoryname' => 'required|string|min:1|max:50|unique:categories,name|regex:/^[a-zA-Z]+$/',
                'categoryshortname' => 'required|string|min:1|max:7|regex:/^[a-zA-Z]+$/',
            ], [
                'categoryname.required' => 'Please provide a category name.',
                'categoryname.string' => 'The category name should consist of valid text characters.',
                'categoryname.regex' => 'The category name should only contain letters without spaces or numbers.',
                'categoryname.min' => 'The category name must contain at least 1 character.',
                'categoryname.max' => 'The category name cannot exceed 50 characters.',
                'categoryname.unique' => 'This category name is already in use, please choose a different one.',

                'categoryshortname.required' => 'A short name for the category is required.',
                'categoryshortname.string' => 'The short name should be valid text.',
                'categoryshortname.regex' => 'The short name should only contain letters without spaces or numbers.',
                'categoryshortname.min' => 'The short name must be at least 1 character.',
                'categoryshortname.max' => 'The short name cannot exceed 7 characters.',
            ]);

            // Redirect back with errors if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            // Handle the file upload for the category image
            $filename = null;
            if ($request->hasFile('categoryimage')) {
                $file = $request->file('categoryimage');
                // Generate a unique filename and store the file in the 'categories' directory
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('categories', $filename, 'public');
            }

            // Create a new category record in the database
            Category::create([
                'name' => $request->input('categoryname'),
                'short_name' => $request->input('categoryshortname'),
                'image' => $filename,
            ]);

            // Redirect to the categories list with a success message
            return redirect()->route('categories')->with('success', 'Category added successfully');
        } catch (\Throwable $throwable) {
            // Log any errors that occur during the process
            \Log::error($throwable->getMessage());
            // Redirect back with an error message
            return redirect()->back()->with('error', 'An error occurred while adding the category.');
        }
    }

    public function deleteCategory($id)
    {
        try {
            $category = Category::find($id);
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully']);
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['file' => $throwable->getFile(), 'line' => $throwable->getLine()]); return redirect()->back()->with('error', $throwable->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json($category);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function editCategory(Request $request, $id)
    {
        try {
            // Custom validation messages
        $messages = [
            'categoryname.required' => 'The category name is required.',
            'categoryname.string' => 'The category name must be a string.',
            'categoryname.max' => 'The category name may not be greater than 255 characters.',
            'categoryname.unique' => 'The category name has already been taken.',
            'categoryshortname.required' => 'The short name is required.',
            'categoryshortname.string' => 'The short name must be a string.',
            'categoryshortname.max' => 'The short name may not be greater than 100 characters.',
            'categoryimage.image' => 'The file must be an image.',
            'categoryimage.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'categoryimage.max' => 'The image size may not be greater than 2MB.',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'categoryname' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($id),
                'regex:/^[a-zA-Z]+$/'
            ],
            'categoryshortname' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z]+$/'
            ],
            'categoryimage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Find the category by ID
        $category = Category::findOrFail($id);

        // Update the category details
        $category->name = $request->categoryname;
        $category->short_name = $request->categoryshortname;

        // Check if a new image has been uploaded
        if ($request->hasFile('categoryimage')) {
            $file = $request->file('categoryimage');
            // Generate a unique filename and store the file in the 'categories' directory
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('categories', $filename, 'public');
            $category->image = $filename;
        }

        // Save the updated category
        $category->save();

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully'
        ]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $search = $request->input('search');

        $query = Category::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('short_name', 'like', '%' . $search . '%');
            });
        }

        $categories = $query->orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'categories' => $categories->items(),
            'pagination' => (string) $categories->links(),
        ]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
        }
    }

}




