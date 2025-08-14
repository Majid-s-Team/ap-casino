<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamPlay extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'amount_won',
        'game_played_id',
        'casino_id',
        'person_name',
        'people_involved',
        'photo',
        'user_id'
    ];
    protected $casts = [
        'people_involved' => 'array'
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
