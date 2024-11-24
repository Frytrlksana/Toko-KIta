<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrders extends Model
{
    use HasFactory;

    protected $table = 'detail_orders';

    protected $fillable = ['orders_id', 'products_id', 'quantity', 'price'];

    public function transaksi()
    {
        return $this->belongsTo(Orders::class);
    }

    public function produk()
    {
        return $this->belongsTo(Products::class);
    }
}
