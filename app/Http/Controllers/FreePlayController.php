<?php

namespace App\Http\Controllers;

use App\Models\FreePlay;
use Illuminate\Http\Request;

class FreePlayController extends Controller
{
//    public function index()
//     {
//         $plays = FreePlay::with(['casino:id,name', 'gamePlayed:id,name'])
//             ->where('user_id', auth()->id())
//             ->get();
//         return $this->apiResponse(200, 'All Free Plays', $plays);
//     }
public function index(Request $request)
{
    // dd($request);
    $query = FreePlay::with(['casino:id,name', 'gamePlayed:id,name'])
        ->where('user_id', auth()->id());

    // Filters
  $filters = $request->query();

if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
    $query->whereBetween('date', [$filters['start_date'], $filters['end_date']]);
}

if (!empty($filters['game_played_id'])) {
    $query->where('game_played_id', $filters['game_played_id']);
}

if (!empty($filters['casino_id'])) {
    $query->where('casino_id', $filters['casino_id']);
}

    $plays = $query->get();

    return $this->apiResponse(200, 'Filtered Free Plays', $plays);
}


    public function show($id)
    {
        $play = FreePlay::with(['casino:id,name', 'gamePlayed:id,name'])->find($id);
        return $play
            ? $this->apiResponse(200, 'Free Play Details', $play)
            : $this->apiResponse(404, 'Free Play Not Found');
    }

     public function store(Request $request)
    {
        $request->validate([
            'time' => 'required',
            'date' => 'required|date',
            'am_pm' => 'required|string',
            'casino_id' => 'required|exists:casinos,id',
            'game_played_id' => 'required|exists:game_playeds,id',
            'person_name' => 'nullable|string',
            'fp_amount' => 'nullable|numeric',
            'photo' => 'nullable|url'
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $play = FreePlay::create($data);
        return $this->apiResponse(201, 'Free Play Created', $play);
    }

    public function update(Request $request, $id)
    {
        $play = FreePlay::find($id);
        if (!$play)
            return $this->apiResponse(404, 'Free Play Not Found');

        $request->validate([
            'time' => '',
            'date' => 'date',
            'am_pm' => '',
            'casino_id' => 'exists:casinos,id',
            'game_played_id' => 'exists:game_playeds,id',
            'person_name' => 'string',
            'fp_amount' => 'numeric',
            'cash_out' => 'numeric',
            'photo' => 'nullable|url'
        ]);

        $play->update($request->all());
        return $this->apiResponse(200, 'Free Play Updated', $play);
    }

    public function destroy($id)
    {
        $play = FreePlay::find($id);
        if (!$play)
            return $this->apiResponse(404, 'Free Play Not Found');

        $play->delete();
        return $this->apiResponse(200, 'Free Play Deleted');
    }
}
