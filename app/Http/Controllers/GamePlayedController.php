<?php

namespace App\Http\Controllers;

use App\Models\GamePlayed;
use Illuminate\Http\Request;

class GamePlayedController extends Controller
{
    // public function index()
    // {
    //     $games = GamePlayed::with('category')->where('user_id', auth()->id())->get();
    //     return $this->apiResponse(200, 'All Games', $games);
    // }
    public function index(Request $request)
    {
        // $query = GamePlayed::with('category')->where('user_id', auth()->id());
        $query = GamePlayed::with('category');

        // Filters
        if ($request->query('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        if ($request->query('start_date') && $request->query('end_date')) {
            $query->whereBetween('created_at', [
                $request->query('start_date'),
                $request->query('end_date')
            ]);
        }

        if ($request->query('name')) {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }

        $games = $query->get();

        return $this->apiResponse(200, 'Filtered Games', $games);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $data = $request->only('name', 'category_id');
        $data['user_id'] = auth()->id();
        $game = GamePlayed::create($data);
        return $this->apiResponse(201, 'Game Created', $game);
    }
    public function show($id)
    {
        $game = GamePlayed::with('category')->find($id);
        return $game ? $this->apiResponse(200, 'Game Details', $game) : $this->apiResponse(404, 'Game Not Found');
    }
    public function update(Request $request, $id)
    {
        $game = GamePlayed::find($id);
        if (!$game)
            return $this->apiResponse(404, 'Game Not Found');
        $game->update($request->only('name', 'category_id'));
        return $this->apiResponse(200, 'Game Updated', $game);
    }
    public function destroy($id)
    {
        $game = GamePlayed::find($id);
        if (!$game)
            return $this->apiResponse(404, 'Game Not Found');
        $game->delete();
        return $this->apiResponse(200, 'Game Deleted');
    }
}
