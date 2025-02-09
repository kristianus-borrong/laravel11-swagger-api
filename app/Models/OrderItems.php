<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_order_item',
        'id_order',
        'id_product',
        'quantity',
        'price',
    ];
    
    public $timestamps = false;
}
