<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Http\Requests\StoreItemsRequest;
use App\Http\Requests\StoreMenusCategoryRequest;
use App\Http\Requests\UpdateMenusCategoryRequest;
use App\Http\Resources\MenusCategoryResource;
use App\Models\Item;
use App\Models\MenuCategory;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenusCategoryController extends Controller
{
    use HttpResponses;
    

    public function index()
    {
        $categories = MenuCategory::all(); // Assuming you have a MenuCategory model

        return MenusCategoryResource::collection($categories);
    }

    public function store(StoreMenusCategoryRequest $request)
    {
        // Store the menu category in the database
        $category = MenuCategory::create($request->validated());

        return new MenusCategoryResource($category);
    }

    public function update(UpdateMenusCategoryRequest $request, $id)
    {
        // Logic to update a menu category

        // Find the menu category and update it
        $menuCategory = MenuCategory::findOrFail($id);
        $menuCategory->update($request->validated());

        return new MenusCategoryResource($menuCategory);
    }
    public function destroy($id)
    {
        // Logic to delete a menu category
        $menuCategory = MenuCategory::findOrFail($id);
        $menuCategory->delete();

        return $this->success('','Menu category deleted successfully.');
    }
    public function show($id)
    {
        // Logic to display a single menu category
        $menuCategory = MenuCategory::findOrFail($id);
        $admin = Auth::guard('admin')->user();
        return new MenusCategoryResource($menuCategory);
    }

}
