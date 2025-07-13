<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->apiResponse(200, 'All Categories', Category::all());
    }
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string']);
        $category = Category::create(['name' => $request->name]);
        return $this->apiResponse(201, 'Category Created', $category);
    }
    public function show($id)
    {
        $category = Category::find($id);
        return $category ? $this->apiResponse(200, 'Category Details', $category) : $this->apiResponse(404, 'Category Not Found');
    }
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category)
            return $this->apiResponse(404, 'Category Not Found');
        $category->update($request->only('name'));
        return $this->apiResponse(200, 'Category Updated', $category);
    }
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category)
            return $this->apiResponse(404, 'Category Not Found');
        $category->delete();
        return $this->apiResponse(200, 'Category Deleted');
    }

}
