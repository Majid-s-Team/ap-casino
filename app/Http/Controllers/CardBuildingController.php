<?php

namespace App\Http\Controllers;

use App\Models\CardBuilding;
use Illuminate\Http\Request;

class CardBuildingController extends Controller
{
    public function index(Request $request)
    {
        
        $query = CardBuilding::with('casino')
            ->where('user_id', auth()->id());

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('casino_id')) {
            $query->where('casino_id', $request->casino_id);
        }

        $data = $query->get();
        // dd($data);

        return $this->apiResponse(200, 'Card Building List', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'time' => 'required',
            'am_pm' => 'required|string',
            'date' => 'required|date',
            'casino_id' => 'required|exists:casinos,id',
            'card_name' => 'required|string',
            'pointsEarned' => 'nullable|numeric|min:0',
            'cash_in' => 'nullable|numeric',
            'cash_out' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'total' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        $cardBuilding = CardBuilding::create($data);

        return $this->apiResponse(201, 'Card Building Created', $cardBuilding);
    }

    public function show($id)
    {
        $item = CardBuilding::with('casino:id,name')->find($id);

        return $item
            ? $this->apiResponse(200, 'Card Building Details', $item)
            : $this->apiResponse(404, 'Card Building Not Found');
    }

    public function update(Request $request, $id)
    {
        $item = CardBuilding::find($id);

        if (!$item) {
            return $this->apiResponse(404, 'Card Building Not Found');
        }

        $request->validate([
            'time' => '',
            'am_pm' => '',
            'date' => 'date',
            'casino_id' => 'exists:casinos,id',
            'card_name' => 'string',
            'cash_in' => 'numeric',
            'cash_out' => 'numeric',
            'balance' => 'numeric',
            'total' => 'numeric',
            'notes' => 'nullable|string',
            'pointsEarned' => 'nullable|numeric|min:0',
        ]);

        $item->update($request->all());

        return $this->apiResponse(200, 'Card Building Updated', $item);
    }

    public function destroy($id)
    {
        $item = CardBuilding::find($id);

        if (!$item) {
            return $this->apiResponse(404, 'Card Building Not Found');
        }

        $item->delete();

        return $this->apiResponse(200, 'Card Building Deleted');
    }
}
