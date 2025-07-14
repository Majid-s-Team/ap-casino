<?php

namespace App\Http\Controllers;

use App\Models\{IndividualLog, TeamLog, HandPay, Casino, GamePlayed};
use Illuminate\Http\Request;
use PDF;
use App\Models\{CardBuilding, Expense, FreePlay, SlotSession, TeamPlay, W2gsForm};

class ReportController extends Controller
{
    public function summary(Request $request)
    {
        $userId = auth()->id();

        $filterGame = $request->game_type_id;
        $filterType = $request->filter_type;

        $queryIndividual = IndividualLog::where('user_id', $userId);
        $queryTeam = TeamLog::where('user_id', $userId);


        if ($filterType == 'monthly') {
            $queryIndividual->whereMonth('date_time', now()->month);
            $queryTeam->whereMonth('date_time', now()->month);
        } elseif ($filterType == 'yearly') {
            $queryIndividual->whereYear('date_time', now()->year);
            $queryTeam->whereYear('date_time', now()->year);
        }

        if ($filterGame) {
            $queryIndividual->where('game_type_id', $filterGame);
            $queryTeam->where('game_type_id', $filterGame);
        }

        $totalInvestment = $queryIndividual->sum('investment_amount') + $queryTeam->sum('investment_amount');
        $totalRepayment = $queryIndividual->sum('repayment') + $queryTeam->sum('repayment');
        $totalBalance = $totalRepayment - $totalInvestment;

        $games = GamePlayed::all()->map(function ($game) use ($userId, $filterType) {
            $queryInd = IndividualLog::where('user_id', $userId)->where('game_type_id', $game->id);
            $queryTeam = TeamLog::where('user_id', $userId)->where('game_type_id', $game->id);

            if ($filterType == 'monthly') {
                $queryInd->whereMonth('date_time', now()->month);
                $queryTeam->whereMonth('date_time', now()->month);
            } elseif ($filterType == 'yearly') {
                $queryInd->whereYear('date_time', now()->year);
                $queryTeam->whereYear('date_time', now()->year);
            }

            $profitLoss = $queryInd->sum('balance') + $queryTeam->sum('balance');

            return [
                'game_id' => $game->id,
                'game_name' => $game->name,
                'profit_loss' => $profitLoss
            ];
        });

        return $this->apiResponse(200, 'Summary Report', [
            'total_investment' => $totalInvestment,
            'total_repayment' => $totalRepayment,
            'total_balance' => $totalBalance,
            'games' => $games
        ]);
    }

    public function myReports(Request $request)
    {
        $userId = auth()->id();

        $filters = [
            'game_type_id' => $request->game_type_id,
            'casino_id' => $request->casino_id,
            'from_date' => $request->from_date ?? now()->subYear()->toDateString(),
            'to_date' => $request->to_date ?? now()->toDateString()
        ];

        $individualLogs = IndividualLog::where('user_id', $userId)
            ->when($filters['game_type_id'], fn($q) => $q->where('game_type_id', $filters['game_type_id']))
            ->when($filters['casino_id'], fn($q) => $q->where('casino_id', $filters['casino_id']))
            ->whereBetween('date_time', [$filters['from_date'], $filters['to_date']])
            ->with(['casino', 'game'])
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'date_time' => $log->date_time,
                    'game_name' => $log->game->name ?? '',
                    'casino_name' => $log->casino->name ?? '',
                    'investment_amount' => $log->investment_amount,
                    'repayment' => $log->repayment,
                    'balance' => $log->balance,
                    'note' => $log->note,
                ];
            });

        $teamLogs = TeamLog::where('user_id', $userId)
            ->when($filters['game_type_id'], fn($q) => $q->where('game_type_id', $filters['game_type_id']))
            ->when($filters['casino_id'], fn($q) => $q->where('casino_id', $filters['casino_id']))
            ->whereBetween('date_time', [$filters['from_date'], $filters['to_date']])
            ->with(['casino', 'game'])
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'date_time' => $log->date_time,
                    'game_name' => $log->game->name ?? '',
                    'casino_name' => $log->casino->name ?? '',
                    'investment_amount' => $log->investment_amount,
                    'repayment' => $log->repayment,
                    'balance' => $log->balance,
                    'note' => $log->note,

                ];
            });

        return $this->apiResponse(200, 'My Reports', [
            'individual_logs' => $individualLogs,
            'team_logs' => $teamLogs
        ]);
    }

    public function taxReport(Request $request)
    {
        $userId = auth()->id();

        $from = $request->from_date ?? now()->startOfYear()->toDateString();
        $to = $request->to_date ?? now()->endOfYear()->toDateString();

        $handPays = HandPay::where('user_id', $userId)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        $totalHandpay = $handPays->sum('handpay_amount');
        $totalDeduction = $handPays->sum('deduction');
        $netAmount = $totalHandpay - $totalDeduction;

        if ($request->has('export') && $request->export == 'pdf') {
            $data = [
                'user' => auth()->user()->name,
                'hand_pays' => $handPays,
                'total_handpay' => $totalHandpay,
                'total_deduction' => $totalDeduction,
                'net_amount' => $netAmount,
            ];

            $pdf = PDF::loadView('reports.tax', $data);
            return $pdf->download('tax_report.pdf');
        }

        return $this->apiResponse(200, 'Tax Report', [
            'total_handpay' => $totalHandpay,
            'total_deduction' => $totalDeduction,
            'net_amount' => $netAmount,
            'hand_pays' => $handPays
        ]);
    }

    public function allLogs(Request $request)
    {
        $userId = auth()->id();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $casinoId = $request->input('casino_id');
        $gameId = $request->input('game_id');
        $name = $request->input('name');

        $data = [];

        // Card Building
        if (!$name || $name == 'card_building') {
            $query = CardBuilding::where('user_id', $userId);
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
            if ($casinoId) {
                $query->where('casino_id', $casinoId);
            }
            $data['card_building'] = $query->get();
        }

        // Expense
        if (!$name || $name == 'expense') {
            $query = Expense::with('category')->where('user_id', $userId);
            if ($startDate && $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate]);
            }
            $data['expense'] = $query->get();
        }

        // Free Play
        if (!$name || $name == 'free_play') {
            $query = FreePlay::with(['casino:id,name', 'gamePlayed:id,name'])->where('user_id', $userId);
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
            if ($casinoId) {
                $query->where('casino_id', $casinoId);
            }
            if ($gameId) {
                $query->where('game_played_id', $gameId);
            }
            $data['free_play'] = $query->get();
        }

        // Slot Session
        if (!$name || $name == 'slot_session') {
            $query = SlotSession::with(['casino:id,name', 'gamePlayed:id,name'])->where('user_id', $userId);
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
            if ($casinoId) {
                $query->where('casino_id', $casinoId);
            }
            if ($gameId) {
                $query->where('game_played_id', $gameId);
            }
            $data['slot_session'] = $query->get();
        }

        // Team Play
        if (!$name || $name == 'team_play') {
            $query = TeamPlay::with(['casino:id,name', 'gamePlayed:id,name'])->where('user_id', $userId);
            if ($startDate && $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate]);
            }
            if ($casinoId) {
                $query->where('casino_id', $casinoId);
            }
            if ($gameId) {
                $query->where('game_played_id', $gameId);
            }
            $data['team_play'] = $query->get();
        }

        // Team Log
        if (!$name || $name == 'team_log') {
            $query = TeamLog::with(['game:id,name', 'casino:id,name'])->where('user_id', $userId);
            if ($startDate && $endDate) {
                $query->whereBetween('date_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }
            if ($casinoId) {
                $query->where('casino_id', $casinoId);
            }
            if ($gameId) {
                $query->where('game_type_id', $gameId);
            }
            $data['team_log'] = $query->get();
        }

        // Hand Pay
        if (!$name || $name == 'hand_pay') {
            $query = HandPay::where('user_id', $userId);
            $data['hand_pay'] = $query->get();
        }

        // W2Gs Form
        if (!$name || $name == 'w2gs_form') {
            $query = W2gsForm::with('casino:id,name')->where('user_id', $userId);
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
            if ($casinoId) {
                $query->where('casino_id', $casinoId);
            }
            $data['w2gs_form'] = $query->get();
        }

        return response()->json([
            'status' => 200,
            'message' => 'User Data Grouped',
            'data' => $data
        ]);
    }


}
