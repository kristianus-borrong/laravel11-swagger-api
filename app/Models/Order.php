<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'id_orders',
        'id_customer',
        'date',
        'status',
    ];

    public $timestamps = false;
}
