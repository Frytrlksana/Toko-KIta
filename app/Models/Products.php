<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Products extends Model
{
    use HasFactory;

    // const UPDATED_AT = null;

    protected $table = 'products';

    protected $fillable = [
        'id',
        'category_id',
        'name',
        'desc',
        'image',
        'price',
    ];

    public function DetailOrders()
    {
        return $this->hasMany(DetailOrders::class);
    }



    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            // Hapus foto dari storage saat produk dihapus
            Storage::delete("public/produks/{$product->image}");
        });

        static::creating(function ($product) {
            $product->updated_at = null; // updated_at tetap null saat pembuatan
        });
    }
}
