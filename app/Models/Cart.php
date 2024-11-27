<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Products;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cartDetail()
    {
        return $this->hasMany(CartDetail::class);
    }
}
