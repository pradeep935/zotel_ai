<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = [
    'customer_name',
    'customer_email',
    'room_number',
    'check_in',
    'check_out',
    'total_amount',
];


}
