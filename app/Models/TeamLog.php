<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamLog extends Model
{
    protected $fillable = [
        'user_id', 'game_type_id', 'casino_id', 'team_members', 'date_time',
        'amount', 'investment_amount', 'repayment', 'balance', 'note', 'image'
    ];

    protected $casts = [
        'team_members' => 'array'
    ];

    public function game()
    {
        return $this->belongsTo(GamePlayed::class, 'game_type_id');
    }

    public function casino()
    {
        return $this->belongsTo(Casino::class);
    }
}
