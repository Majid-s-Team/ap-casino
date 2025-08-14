<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'expense_category_id',
        'location',
        'amount',
        'user_id',
         'am_pm',   
        'time',
        ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }
        public function user()
    {
        return $this->belongsTo(User::class);
    }

}
