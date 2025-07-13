<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    // public function index()
    // {
    //     return $this->apiResponse(200, 'All Expenses', Expense::with('category:id,name')->get());
    // }
    // public function index()
    // {
    //     $expenses = Expense::with('category:id,name')->where('user_id', auth()->id())->get();
    //     return $this->apiResponse(200, 'All Expenses', $expenses);
    // }
    public function index(Request $request)
{
    $query = Expense::with('category:id,name')->where('user_id', auth()->id());

    if ($request->query('expense_category_id')) {
        $query->where('expense_category_id', $request->query('expense_category_id'));
    }

    if ($request->query('start_date') && $request->query('end_date')) {
        $query->whereBetween('start_date', [
            $request->query('start_date'), 
            $request->query('end_date')
        ]);
    }

    if ($request->query('location')) {
        $query->where('location', 'LIKE', '%' . $request->query('location') . '%');
    }

    $expenses = $query->get();

    return $this->apiResponse(200, 'Filtered Expenses', $expenses);
}

    public function show($id)
    {
        $expense = Expense::with('category:id,name')->find($id);
        if (!$expense)
            return $this->apiResponse(404, 'Expense Not Found');

        return $this->apiResponse(200, 'Expense Details', $expense);
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'location' => 'nullable|string',
            'amount' => 'nullable|numeric'
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $expense = Expense::create($data);
        return $this->apiResponse(201, 'Expense Created', $expense);
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::find($id);
        if (!$expense)
            return $this->apiResponse(404, 'Expense Not Found');

        $request->validate([
            'start_date' => 'date',
            'end_date' => 'date',
            'expense_category_id' => 'exists:expense_categories,id',
            'location' => 'string',
            'amount' => 'numeric'
        ]);

        $expense->update($request->all());
        return $this->apiResponse(200, 'Expense Updated', $expense);
    }
    public function destroy($id)
    {
        $expense = Expense::find($id);
        if (!$expense)
            return $this->apiResponse(404, 'Expense Not Found');

        $expense->delete();
        return $this->apiResponse(200, 'Expense Deleted');
    }
}