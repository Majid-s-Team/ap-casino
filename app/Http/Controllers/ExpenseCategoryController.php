<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::all();
        return $this->apiResponse(200, 'All Expense Categories', $categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name'
        ]);

        $category = ExpenseCategory::create([
            'name' => $request->name
        ]);

        return $this->apiResponse(201, 'Expense Category Created', $category);
    }

    public function show($id)
    {
        $category = ExpenseCategory::find($id);

        return $category
            ? $this->apiResponse(200, 'Expense Category Details', $category)
            : $this->apiResponse(404, 'Expense Category Not Found');
    }

    public function update(Request $request, $id)
    {
        $category = ExpenseCategory::find($id);

        if (!$category)
            return $this->apiResponse(404, 'Expense Category Not Found');

        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name,' . $id
        ]);

        $category->update([
            'name' => $request->name
        ]);

        return $this->apiResponse(200, 'Expense Category Updated', $category);
    }

    public function destroy($id)
    {
        $category = ExpenseCategory::find($id);

        if (!$category)
            return $this->apiResponse(404, 'Expense Category Not Found');

        $category->delete();

        return $this->apiResponse(200, 'Expense Category Deleted');
    }
}
