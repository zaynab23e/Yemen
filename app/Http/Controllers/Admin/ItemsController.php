<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Http\Requests\StoreItemsRequest;
use App\Http\Requests\UpdateItemsRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Models\MenuCategory;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemsController extends Controller
{
    use HttpResponses;

    public function index()
    {
        $items = Item::with('menuCategory')->get(); // Assuming you have an Item model

        return ItemResource::collection($items);
    }
    // public function create()
    // {
    //     $admin = Auth::guard('admin')->user();
    //     $MenuCategories = MenuCategory::all();

    //     // Logic to show the form for creating a new item
    //     return view('DashBoard.Items.create', compact('admin', 'MenuCategories'));
    // }
    public function store(StoreItemsRequest $request)
    {
        // dd($request->all());
       if ($request->hasFile('image')) {
            $file = $request->file('image'); // You have an UploadedFile instance
            $filename = time() . '.' . $file->getClientOriginalExtension();

            $file->storeAs('public/items', $filename);

            $item = Item::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $filename,
                'menucategory_id' =>$request->category_id,
                'type' => $request->type
            ]);
        }
        if (!$item) {
            return response()->json([
                'status' => 'Error has occurred...',
                'message' => 'Item creation failed',
                'data' => ''
            ], 500);
        }


        return new ItemResource($item);
    }
    // public function edit($id)
    // {
    //     // Logic to show the form for editing a menu category
    //     $item = Item::findOrFail($id);
    //     $admin = Auth::guard('admin')->user();
    //     $MenuCategories = MenuCategory::all();

    //     return view('DashBoard.Items.edit', compact('item','admin','MenuCategories'));
    // }
public function update(UpdateItemsRequest $request, $id)
{
    $item = Item::findOrFail($id);

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/items', $filename);
        $item->image = $filename;
    } else {
        $item->image = $request->old_image; // fallback to old image
    }

    $item->name = $request->name;
    $item->description = $request->description;
    $item->price = $request->price;
    $item->menucategory_id = $request->category_id;

    if (!$item->save()) {
        return redirect()->route('items.index')->with('error', 'Item update failed');
    }

    return new ItemResource($item);
}

    public function destroy($id)
    {
        // Logic to delete a menu category
        $item = Item::findOrFail($id);
        $item->delete();

        return $this->success('','item deleted successfully');
    }
    public function show($id)
    {
        // Logic to display a single menu category
        $item = Item::findOrFail($id);
        return new ItemResource($item);
    }
}