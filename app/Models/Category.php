<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'hex_code'];


    public function games()
    {
        return $this->hasMany(GamePlayed::class);
    }
}
