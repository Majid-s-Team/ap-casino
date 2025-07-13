<?php

namespace App\Http\Controllers;

use App\Models\W2gsForm;
use Illuminate\Http\Request;

class W2gsFormController extends Controller
{
    // public function index()
    // {
    //     $forms = W2gsForm::with('casino:id,name')
    //         ->where('user_id', auth()->id())
    //         ->get();
    //     return $this->apiResponse(200, 'All W2Gs Forms', $forms);
    // }
    public function index(Request $request)
{
    $query = W2gsForm::with('casino:id,name')
        ->where('user_id', auth()->id());

    // Date Filter
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('date', [$request->start_date, $request->end_date]);
    }

    // Casino Filter
    if ($request->filled('casino_id')) {
        $query->where('casino_id', $request->casino_id);
    }

    $forms = $query->get();

    return $this->apiResponse(200, 'Filtered W2Gs Forms', $forms);
}


    public function show($id)
    {
        $form = W2gsForm::with('casino:id,name')->find($id);
        return $form
            ? $this->apiResponse(200, 'W2Gs Form Details', $form)
            : $this->apiResponse(404, 'W2Gs Form Not Found');
    }

    public function store(Request $request)
    {
        $request->validate([
            'time' => 'required',
            'date' => 'required|date',
            'am_pm' => 'required',
            'casino_id' => 'required|exists:casinos,id',
            'winning_amount' => 'required|numeric',
            'fed_tax' => 'required|numeric',
            'state_tax' => 'required|numeric',
            'local_tax' => 'required|numeric',
            'photo' => 'nullable|url'
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $form = W2gsForm::create($data);

        return $this->apiResponse(201, 'W2Gs Form Created', $form);
    }

    public function update(Request $request, $id)
    {
        $form = W2gsForm::find($id);
        if (!$form) {
            return $this->apiResponse(404, 'W2Gs Form Not Found');
        }

        $request->validate([
            'time' => '',
            'date' => 'date',
            'am_pm' => '',
            'casino_id' => 'exists:casinos,id',
            'winning_amount' => 'numeric',
            'fed_tax' => 'numeric',
            'state_tax' => 'numeric',
            'local_tax' => 'numeric',
            'photo' => 'nullable|url'
        ]);

        $form->update($request->all());

        return $this->apiResponse(200, 'W2Gs Form Updated', $form);
    }

    public function destroy($id)
    {
        $form = W2gsForm::find($id);
        if (!$form)
            return $this->apiResponse(404, 'W2Gs Form Not Found');

        $form->delete();
        return $this->apiResponse(200, 'W2Gs Form Deleted');
    }
}
