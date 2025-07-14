<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardBuilding extends Model
{
    use HasFactory;

    protected $fillable = [
        'time',
        'am_pm',
        'date',
        'casino_id',
        'card_name',
        'cash_in',
        'cash_out',
        'balance',
        'total',
        'notes',
        'user_id'
    ];

    public function casino()
    {
        return $this->belongsTo(Casino::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
