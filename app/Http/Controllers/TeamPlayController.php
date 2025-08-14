<?php

namespace App\Http\Controllers;

use App\Models\TeamPlay;
use Illuminate\Http\Request;

class TeamPlayController extends Controller
{
//    public function index()
//     {
//         $plays = TeamPlay::with(['casino:id,name', 'gamePlayed:id,name'])
//             ->where('user_id', auth()->id())
//             ->get();

//         return $this->apiResponse(200, 'All Team Plays', $plays);
//     }
public function index(Request $request)
{
    $query = TeamPlay::with(['casino:id,name', 'gamePlayed:id,name'])
        ->where('user_id', auth()->id());

    // Date Filter
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
    }

    // Casino Filter
    if ($request->filled('casino_id')) {
        $query->where('casino_id', $request->casino_id);
    }

    // Game Played Filter
    if ($request->filled('game_played_id')) {
        $query->where('game_played_id', $request->game_played_id);
    }

    $plays = $query->get();

    return $this->apiResponse(200, 'Filtered Team Plays', $plays);
}


    public function show($id)
    {
        $play = TeamPlay::with(['casino:id,name', 'gamePlayed:id,name'])->find($id);
        return $play
            ? $this->apiResponse(200, 'Team Play Details', $play)
            : $this->apiResponse(404, 'Team Play Not Found');
    }

     public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'amount_won' => 'nullable|numeric',
            'casino_id' => 'nullable|exists:casinos,id',
            'game_played_id' => 'nullable|exists:game_playeds,id',
            'person_name' => 'nullable|string',
            'photo' => 'nullable|url',
            'people_involved.*' => 'string|max:255', 

        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $play = TeamPlay::create($data);

        return $this->apiResponse(201, 'Team Play Created', $play);
    }
    public function update(Request $request, $id)
    {
        $play = TeamPlay::find($id);
        if (!$play)
            return $this->apiResponse(404, 'Team Play Not Found');

        $request->validate([
            'start_date' => 'date',
            'end_date' => 'date',
            'amount_won' => 'numeric',
            'casino_id' => 'exists:casinos,id',
            'game_played_id' => 'exists:game_playeds,id',
            'person_name' => 'string',
            'photo' => 'nullable|url',
            'people_involved.*' => 'string|max:255', 

        ]);

        $play->update($request->all());

        return $this->apiResponse(200, 'Team Play Updated', $play);
    }

    public function destroy($id)
    {
        $play = TeamPlay::find($id);
        if (!$play)
            return $this->apiResponse(404, 'Team Play Not Found');

        $play->delete();
        return $this->apiResponse(200, 'Team Play Deleted');
    }
}
