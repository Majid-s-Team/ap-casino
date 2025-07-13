<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class W2gsForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'time',
        'date',
        'am_pm',
        'casino_id',
        'winning_amount',
        'fed_tax',
        'state_tax',
        'local_tax',
        'photo',
        'user_id'
    ];
    public function casino()
    {
        return $this->belongsTo(Casino::class, 'casino_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }



}
