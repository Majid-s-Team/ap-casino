<?php

namespace App\Http\Controllers;

use App\Models\{IndividualLog, TeamLog, HandPay};
use Illuminate\Http\Request;

class LogController extends Controller
{
    // public function individualLogs(Request $request)
    // {
    //     $logs = IndividualLog::with(['game', 'casino'])
    //         ->where('user_id', auth()->id())
    //         ->get();

    //     return $this->apiResponse(200, 'Individual Logs', $logs);
    // }
    public function individualLogs(Request $request)
{
    $query = IndividualLog::with(['game', 'casino'])
        ->where('user_id', auth()->id());

    // Filters
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('date_time', [$request->start_date, $request->end_date]);
    }

    if ($request->filled('game_type_id')) {
        $query->where('game_type_id', $request->game_type_id);
    }

    if ($request->filled('casino_id')) {
        $query->where('casino_id', $request->casino_id);
    }

    $logs = $query->get();

    return $this->apiResponse(200, 'Individual Logs', $logs);
}



    public function storeIndividual(Request $request)
    {
        $data = $request->validate([
            'game_type_id' => 'required|exists:game_playeds,id',
            'casino_id' => 'required|exists:casinos,id',
            'date_time' => 'required|date',
            'amount' => 'required|numeric',
            'investment_amount' => 'required|numeric',
            'repayment' => 'required|numeric',
            'balance' => 'required|numeric',
            'note' => 'nullable|string',
            'image' => 'nullable|url',
        ]);

        $data['user_id'] = auth()->id();
        $log = IndividualLog::create($data);

        return $this->apiResponse(201, 'Individual Log Created', $log);
    }
    public function updateIndividual(Request $request, $id)
{
    $log = IndividualLog::where('user_id', auth()->id())->find($id);

    if (!$log) {
        return $this->apiResponse(404, 'Individual Log Not Found');
    }

    $data = $request->validate([
        'game_type_id' => 'sometimes|exists:game_playeds,id',
        'casino_id' => 'sometimes|exists:casinos,id',
        'date_time' => 'sometimes|date',
        'amount' => 'sometimes|numeric',
        'investment_amount' => 'sometimes|numeric',
        'repayment' => 'sometimes|numeric',
        'balance' => 'sometimes|numeric',
        'note' => 'nullable|string',
        'image' => 'nullable|url',
    ]);

    $log->update($data);

    return $this->apiResponse(200, 'Individual Log Updated', $log);
}
public function deleteIndividual($id)
{
    $log = IndividualLog::where('user_id', auth()->id())->find($id);

    if (!$log) {
        return $this->apiResponse(404, 'Individual Log Not Found');
    }

    $log->delete();

    return $this->apiResponse(200, 'Individual Log Deleted');
}


    // TEAM LOG
    // public function teamLogs(Request $request)
    // {
    //     $logs = TeamLog::with(['game', 'casino'])
    //         ->where('user_id', auth()->id())
    //         ->get();

    //     return $this->apiResponse(200, 'Team Logs', $logs);
    // }
    public function teamLogs(Request $request)
{
    $query = TeamLog::with(['game', 'casino'])
        ->where('user_id', auth()->id());

    // Filters
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('date_time', [$request->start_date, $request->end_date]);
    }

    if ($request->filled('game_type_id')) {
        $query->where('game_type_id', $request->game_type_id);
    }

    if ($request->filled('casino_id')) {
        $query->where('casino_id', $request->casino_id);
    }

    $logs = $query->get();

    return $this->apiResponse(200, 'Team Logs', $logs);
}


    public function storeTeam(Request $request)
    {
        $data = $request->validate([
            'game_type_id' => 'required|exists:game_playeds,id',
            'casino_id' => 'required|exists:casinos,id',
            'team_members' => 'required|array',
            'date_time' => 'required|date',
            'amount' => 'required|numeric',
            'investment_amount' => 'required|numeric',
            'repayment' => 'required|numeric',
            'balance' => 'required|numeric',
            'note' => 'nullable|string',
            'image' => 'nullable|url',
        ]);

        $data['user_id'] = auth()->id();
        $log = TeamLog::create($data);

        return $this->apiResponse(201, 'Team Log Created', $log);
    }
public function updateTeam(Request $request, $id)
{
    $log = TeamLog::where('user_id', auth()->id())->find($id);

    if (!$log) {
        return $this->apiResponse(404, 'Team Log Not Found');
    }

    $data = $request->validate([
        'game_type_id' => 'sometimes|exists:game_playeds,id',
        'casino_id' => 'sometimes|exists:casinos,id',
        'team_members' => 'sometimes|array',
        'date_time' => 'sometimes|date',
        'amount' => 'sometimes|numeric',
        'investment_amount' => 'sometimes|numeric',
        'repayment' => 'sometimes|numeric',
        'balance' => 'sometimes|numeric',
        'note' => 'nullable|string',
        'image' => 'nullable|url',
    ]);

    $log->update($data);

    return $this->apiResponse(200, 'Team Log Updated', $log);
}
public function deleteTeam($id)
{
    $log = TeamLog::where('user_id', auth()->id())->find($id);

    if (!$log) {
        return $this->apiResponse(404, 'Team Log Not Found');
    }

    $log->delete();

    return $this->apiResponse(200, 'Team Log Deleted');
}

    // HAND PAY
    // public function handPays(Request $request)
    // {
    //     $logs = HandPay::where('user_id', auth()->id())->get();
    //     return $this->apiResponse(200, 'Hand Pays', $logs);
    // }
public function handPays(Request $request)
{
    $query = HandPay::where('user_id', auth()->id());

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
    }

    if ($request->filled('title')) {
        $query->where('title', 'like', '%' . $request->title . '%');
    }

    $logs = $query->get();

    return $this->apiResponse(200, 'Hand Pays', $logs);
}

    public function storeHandPay(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'handpay_amount' => 'required|numeric',
            'payout' => 'required|numeric',
            'deduction' => 'required|numeric',
            'description' => 'nullable|string'
        ]);

        $data['user_id'] = auth()->id();
        $log = HandPay::create($data);

        return $this->apiResponse(201, 'Hand Pay Created', $log);
    }
    public function updateHandPay(Request $request, $id)
{
    $log = HandPay::where('user_id', auth()->id())->find($id);

    if (!$log) {
        return $this->apiResponse(404, 'Hand Pay Not Found');
    }

    $data = $request->validate([
        'title' => 'sometimes|string',
        'handpay_amount' => 'sometimes|numeric',
        'payout' => 'sometimes|numeric',
        'deduction' => 'sometimes|numeric',
        'description' => 'nullable|string'
    ]);

    $log->update($data);

    return $this->apiResponse(200, 'Hand Pay Updated', $log);
}
public function deleteHandPay($id)
{
    $log = HandPay::where('user_id', auth()->id())->find($id);

    if (!$log) {
        return $this->apiResponse(404, 'Hand Pay Not Found');
    }

    $log->delete();

    return $this->apiResponse(200, 'Hand Pay Deleted');
}

}
