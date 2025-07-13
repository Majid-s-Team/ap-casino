<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndividualLog extends Model
{
    protected $fillable = [
        'user_id', 'game_type_id', 'casino_id', 'date_time', 'amount',
        'investment_amount', 'repayment', 'balance', 'note', 'image'
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
