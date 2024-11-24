<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = ['users_id', 'status', 'no_telepon', 'total'];

    // Relasi dengan tabel user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan tabel detail_transaksi
    public function detailTransaksi()
    {
        return $this->hasMany(DetailOrders::class);
    }

    // Override boot method untuk menangani created_at dan updated_at
    protected static function boot()
    {
        parent::boot();

        // Saat transaksi baru dibuat, set updated_at menjadi null
        static::creating(function ($Orders) {
            $Orders->updated_at = null; // updated_at tetap null saat pembuatan
        });

    }
}
