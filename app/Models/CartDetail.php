<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'qty',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'carts_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'products_id');
    }
}
