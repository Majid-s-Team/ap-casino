<?php

namespace App\Http\Controllers;

use App\Models\SlotSession;
use Illuminate\Http\Request;

class SlotSessionController extends Controller
{
//    public function index()
//     {
//         $sessions = SlotSession::with(['casino:id,name', 'gamePlayed:id,name'])
//             ->where('user_id', auth()->id())
//             ->get();

//         return $this->apiResponse(200, 'All Slot Sessions', $sessions);
//     }
public function index(Request $request)
{
    $query = SlotSession::with(['casino:id,name', 'gamePlayed:id,name'])
        ->where('user_id', auth()->id());

    // Date Filter
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('date', [$request->start_date, $request->end_date]);
    }

    // Casino Filter
    if ($request->filled('casino_id')) {
        $query->where('casino_id', $request->casino_id);
    }

    // Game Played Filter
    if ($request->filled('game_played_id')) {
        $query->where('game_played_id', $request->game_played_id);
    }

    $sessions = $query->get();

    return $this->apiResponse(200, 'Filtered Slot Sessions', $sessions);
}

    public function show($id)
    {
        $session = SlotSession::with(['casino:id,name', 'gamePlayed:id,name'])->find($id);

        return $session
            ? $this->apiResponse(200, 'Slot Session Details', $session)
            : $this->apiResponse(404, 'Slot Session Not Found');
    }
       public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'am_pm' => 'required|string',
            'casino_id' => 'required|exists:casinos,id',
            'game_played_id' => 'required|exists:game_playeds,id',
            'ticket_in' => 'nullable|numeric',
            'cash_added' => 'required|numeric',
            'cash_in' => 'nullable|numeric',
            'cash_out' => 'required|numeric',
            'balance' => 'nullable|numeric',
            'total_points' => 'nullable|numeric',
            'attachment' => 'nullable|url',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $data['attachment'] = asset('storage/' . $path);
        }

        $session = SlotSession::create($data);

        return $this->apiResponse(201, 'Slot Session Created', $session);
    }


    public function update(Request $request, $id)
    {
        $session = SlotSession::find($id);
        if (!$session)
            return $this->apiResponse(404, 'Slot Session Not Found');

        $request->validate([
            'date' => 'date',
            'time' => '',
            'am_pm' => 'string',
            'casino_id' => 'exists:casinos,id',
            'game_played_id' => 'exists:game_playeds,id',
            'ticket_in' => 'numeric',
            'cash_added' => 'numeric',
            'cash_in' => 'numeric',
            'cash_out' => 'numeric',
            'balance' => 'numeric',
            'total_points' => 'numeric',
            'attachment' => 'nullable|url',
            'notes' => 'nullable|string',
        ]);

        // $data = $request->except('attachment');
        $data = $request->all();

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $data['attachment'] = asset('storage/' . $path);
        }

        $session->update($data);

        return $this->apiResponse(200, 'Slot Session Updated', $session);
    }

    public function destroy($id)
    {
        $session = SlotSession::find($id);
        if (!$session)
            return $this->apiResponse(404, 'Slot Session Not Found');
        $session->delete();
        return $this->apiResponse(200, 'Slot Session Deleted');
    }
}
