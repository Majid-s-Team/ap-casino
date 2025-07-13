<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlotSession extends Model
{
    use HasFactory;
    protected $fillable = [
        'time',
        'date',
        'am_pm',
        'casino_id',
        'game_played_id',
        'ticket_in',
        'cash_added',
        'cash_in',
        'cash_out',
        'balance',
        'total_points',
        'attachment',
        'notes',
        'user_id'
    ];

    // Relationships
    public function casino()
    {
        return $this->belongsTo(Casino::class);
    }
        public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function gamePlayed()
    {
        return $this->belongsTo(GamePlayed::class);
    }

}
