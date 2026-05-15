<?php

namespace App\Http\Controllers\Modules;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductItem;
use App\Models\ProductType;
use App\Models\Service;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    //
    public function index()
    {
        try {
            $clothes_datas = ProductItem::where('product_id', 1)->get();
        $upholstery_datas = ProductItem::where('product_id', 2)->get();
        $footwearandbags = ProductItem::where('product_id', 3)->get();
        $others = ProductItem::where('product_id', 4)->get();
        $laundries = ProductItem::where('product_id', 5)->get();
        $services = Service::all();
        // dd($clothes_data);
        return view('erp.categorylist', ['clothes_datas' => $clothes_datas, 'upholstery_datas' => $upholstery_datas, 'footwearandbags' => $footwearandbags, 'others' => $others, 'services' => $services, 'laundries' => $laundries]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function fetchClothesData(Request $request)
    {
        try {
            // dd($request->all());
            // Fetch data from the database and filter based on the selected option
            $query = ProductCategory::select('product_categories.id as product_cat_id', 'product_categories.name as product_name', 'product_categories.price', 'operations.name as service_name', 'operations.id as operation_id')
                ->where('product_items.product_id', 1)
            ->join('product_items', 'product_categories.product_item_id', '=', 'product_items.id')
            ->join('operations', 'operations.id', '=', 'product_categories.operation_id');
        if ($request->has('option')) {
            $selectedOption = $request->input('option');
            // Example: Filter data based on the selected option
            $query->where('product_items.id', $selectedOption);
        }
        $data = $query->get();

        // Return data as JSON response
        return response()->json($data);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function fetchUpholsteryData(Request $request)
    {
        try {
            // dd($request->all());
            // Fetch data from the database and filter based on the selected option
            $query = ProductCategory::select('product_categories.id as product_cat_id', 'product_categories.name as product_name', 'product_categories.price', 'operations.name as service_name', 'operations.id as operation_id')
                ->where('product_items.product_id', 2)
            ->join('product_items', 'product_categories.product_item_id', '=', 'product_items.id')
            ->join('operations', 'operations.id', '=', 'product_categories.operation_id');
        if ($request->has('option')) {
            $selectedOption = $request->input('option');
            // Example: Filter data based on the selected option
            $query->where('product_items.id', $selectedOption);
        }
        $data = $query->get();

        // Return data as JSON response
        return response()->json($data);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function fetchFootBagData(Request $request)
    {
        try {
            // Fetch data from the database and filter based on the selected option
            $query = ProductCategory::select('product_categories.id as product_cat_id', 'product_categories.name as product_name', 'product_categories.price', 'operations.name as service_name', 'operations.id as operation_id')
                ->where('product_items.product_id', 3)
            ->join('product_items', 'product_categories.product_item_id', '=', 'product_items.id')
            ->join('operations', 'operations.id', '=', 'product_categories.operation_id');
        if ($request->has('option')) {
            $selectedOption = $request->input('option');
            // Example: Filter data based on the selected option
            $query->where('product_items.id', $selectedOption);
        }
        $data = $query->get();

        // Return data as JSON response
        return response()->json($data);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function fetchOtherData(Request $request)
    {
        try {
            // dd($request->all());
            // Fetch data from the database and filter based on the selected option
            $query = ProductCategory::select('product_categories.id as product_cat_id', 'product_categories.name as product_name', 'product_categories.price', 'operations.name as service_name', 'operations.id as operation_id')
                ->where('product_items.product_id', 4)
            ->join('product_items', 'product_categories.product_item_id', '=', 'product_items.id')
            ->join('operations', 'operations.id', '=', 'product_categories.operation_id');
        if ($request->has('option')) {
            $selectedOption = $request->input('option');
            // Example: Filter data based on the selected option
            $query->where('product_items.id', $selectedOption);
        }
        $data = $query->get();

        // Return data as JSON response
        return response()->json($data);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function fetchLaundryData(Request $request)
    {
        try {
            // dd($request->all());
            // Fetch data from the database and filter based on the selected option
            $query = ProductCategory::select('product_categories.id as product_cat_id', 'product_categories.name as product_name', 'product_categories.price', 'operations.name as service_name', 'operations.id as operation_id')
                ->where('product_items.product_id', 5)
            ->join('product_items', 'product_categories.product_item_id', '=', 'product_items.id')
            ->join('operations', 'operations.id', '=', 'product_categories.operation_id');
        if ($request->has('option')) {
            $selectedOption = $request->input('option');
            // Example: Filter data based on the selected option
            $query->where('product_items.id', $selectedOption);
        }
        $data = $query->get();

        // Return data as JSON response
        return response()->json($data);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function deleteClothes(Request $request)
    {
        $id = $request->cloth_id;
        // dd($id);
        try {
            ProductCategory::where('id', $id)->delete();
            return response()->json(['message' => 'Items deleted successfully']);
        } catch (\Throwable $throwable) {
            return response()->json(['error' => $throwable->getMessage()], 500);
        }
    }

    public function editItems(Request $request)
    {
        // dd($request->all());
        $id = $request->item_id;
        try {
            $items = ProductCategory::findOrFail($id);
            // dd($items);
            $items->name = $request->name;
            $items->operation_id = $request->service;
            $items->price = $request->price;
            $items->save();
            return redirect()->back()->with('success', 'Items updated successfully');
        } catch (\Throwable $throwable) {
            return response()->json(['error' => $throwable->getMessage()], 500);
        }
    }

    public function addcategory()
    {
        try {
            $products = Product::all();
            $services = Service::all();
            $producttypes = ProductType::all();
            return view('erp.category', ['products' => $products, 'services' => $services, 'producttypes' => $producttypes]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function storeCategory(Request $request)
    {
        try {
            $currentTimestamp = Carbon::now();
        $category = $request->category;
        // dd($category);
        foreach ($category as $cat_key => $cat) {
            $items = $request->item_name[$cat_key];
            // $category = $request->category[$i];
            foreach ($items as $key => $item) {
                $available_data = ProductItem::where('name', $request->item_name[$cat_key][$key])->first();
                // dd($request->hasFile('image.'.$cat_key.'.'.$key));
                if ($available_data) {
                    $id = $available_data->id;
                } else {
                    $data = [];
                    if ($request->hasFile('image.' . $cat_key . '.' . $key)) {
                        $image = $request->file('image.' . $cat_key . '.' . $key);
                        $imageName = time() . '_' . $image->getClientOriginalName();
                        $image->move(public_path('images/categories_img'), $imageName);
                        $data['image'] = $imageName;
                    }
                    $data['product_id'] = $cat;
                    $data['name'] = $request->item_name[$cat_key][$key];
                    $data['created_at'] = $currentTimestamp;
                    $data['updated_at'] = $currentTimestamp;
                    $id = ProductItem::insertGetId($data);
                }

                $datas = [];
                $datas['product_item_id'] = $id;
                $datas['operation_id'] = $request->service[$cat_key][$key];
                $datas['name'] = $request->item_type[$cat_key][$key];
                $datas['price'] = $request->price[$cat_key][$key];
                $datas['created_at'] = $currentTimestamp;
                $datas['updated_at'] = $currentTimestamp;

                ProductCategory::insert($datas);
            }
        }
        // return redirect()->back();
        return redirect()->route('categorylist')->with('success', 'Categories and items added successfully');
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
        }
    }
}




