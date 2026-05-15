<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemDetail;
use App\Models\Category;
use App\Models\Services;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    //
    public function index(Request $request)
    {
        // Initialize the query for the ItemDetail model
        $query = ItemDetail::query();

        // Join with items table to get item name
        $query->join('items', 'item_detail.item_id', '=', 'items.id')
            ->select('item_detail.*', 'items.name as item_name');

        // Handle non-AJAX requests: fetch items and render the view
        $items = $query->orderBy('item_detail.id', 'desc')->paginate(10);

        return view('admin.item.item', compact('items'));
    }


    public function addItems(Request $request)
    {
        $categorys = Category::get();
        $services = Services::get();
        return view('admin.item.itemAdd', compact('categorys','services'));
    }


    public function storeItems(Request $request)
    {
        // Validation rules and messages
        $rules = [
            'item_name' => 'required|string|max:255',
            'itemdetail.*.category' => 'required|string|exists:categories,name',
            'itemdetail.*.service.*' => 'required|string|exists:services,name',
            'itemdetail.*.price.*' => 'required|numeric|min:0',
        ];

        $messages = [
            'item_name.required' => 'The item name is required.',
            'item_name.string' => 'The item name must be a string.',
            'item_name.max' => 'The item name may not be greater than 255 characters.',
            'itemdetail.*.category.required' => 'The category field is required.',
            'itemdetail.*.category.exists' => 'The selected category is invalid.',
            'itemdetail.*.service.*.required' => 'Each service is required.',
            'itemdetail.*.service.*.exists' => 'The selected service is invalid.',
            'itemdetail.*.price.*.required' => 'The price field is required.',
            'itemdetail.*.price.*.numeric' => 'The price must be a number.',
            'itemdetail.*.price.*.min' => 'The price must be at least 0.',
        ];

        // Validate the request with custom messages
        $validatedData = $request->validate($rules, $messages);

        // Handle file upload if any
        $filename = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('products', $filename, 'public');
        }

        // Insert into Item table and get the last inserted ID
        $dataItem = [
            'name' => $request->item_name,
        ];

        $lastInsertedId = Item::insertGetId($dataItem);

        // Insert item details into the ItemDetail table
        $itemDetails = $request->input('itemdetail');

        foreach ($itemDetails as $detail) {
            if(!empty($detail['service'])){
                foreach ($detail['service'] as $index => $service) {
                    $itemDetail = new ItemDetail();
                    $itemDetail->item_id = $lastInsertedId;  // Assign the foreign key from Item table
                    $itemDetail->category = $detail['category'];
                    $itemDetail->service = $service;
                    $itemDetail->price = $detail['price'][$index];  // Match the price with service

                    // Save each item detail
                    $itemDetail->save();
                }
            }
        }

        // Redirect back with success message
        return redirect()->route('items')->with('success', 'Item successfully added');
    }


    public function editItem(Request $request , $id)
    {
        $Intemdetails = ItemDetail::join('items', 'item_detail.item_id', '=', 'items.id')
        ->select('item_detail.*', 'items.name as item_name')
        ->where('item_detail.id', $id)
        ->first();

        return view('admin.item.itemEdit', compact('Intemdetails'));
    }

    public function updateItems(Request $request)
    {
        // Step 1: Define Validation Rules and Messages
        $rules = [
            'item_name' => 'required|string|max:255',
            'item_category' => 'required|string',
            'item_service' => 'required|string',
            'item_price' => 'required|numeric|min:0',
        ];

        // Step 2: Validate the request with custom messages
        $validatedData = $request->validate($rules);

        // Step 3: Insert into Item table and get the last inserted ID
        $dataItem = [
            'name' => $request->item_name,
        ];

        Item::where('id', $request->item_id)->update($dataItem);

        // Step 4: Insert item details into the ItemDetail table

        $updatedataItem = [
            'price' => $request->item_price,
        ];

        ItemDetail::where('id', $request->id)->update($updatedataItem);


        // Redirect back with success message
        return redirect()->route('items')->with('success', 'Item successfully added');
    }



}
