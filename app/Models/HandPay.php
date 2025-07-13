<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HandPay extends Model
{
    protected $fillable = [
        'user_id', 'title', 'handpay_amount', 'payout', 'deduction', 'description'
    ];
}
