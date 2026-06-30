<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name', 'base_price', 'seat_price',
        'stripe_price_base', 'stripe_price_seat',
    ];
}
