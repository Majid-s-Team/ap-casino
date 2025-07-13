<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Casino extends Model
{
    use HasFactory;
    protected $fillable = ['name','location','image'];

    public function freePlays()
    {
        return $this->hasMany(FreePlay::class);
    }
}
