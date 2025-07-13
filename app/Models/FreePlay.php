<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreePlay extends Model
{
    use HasFactory;

    protected $fillable = [
    'time',
    'date',
    'am_pm',
    'casino_id',
    'game_played_id',
    'person_name',
    'fp_amount',
    'cash_out',
    'photo',
    'user_id' 
];


    public function casino()
    {
        return $this->belongsTo(Casino::class);
    }
    public function gamePlayed()
    {
        return $this->belongsTo(GamePlayed::class);
    }
        public function user()
    {
        return $this->belongsTo(User::class);
    }

}
