<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamePlayed extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'category_id','user_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
        public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function freePlays()
    {
        return $this->hasMany(FreePlay::class);
    }
}
