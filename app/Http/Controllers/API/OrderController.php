<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Orders;
use App\Models\Products;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $carts = Cart::find($request->cart_id);

        if (!$carts) {
            return response()->json(['error' => 'Keranjang tidak ditemukan'], 404);
        }

        $subtotal = 0;
        foreach ($carts->cartDetail as $cartDetail) {
            $product = Products::find($cartDetail->product_id);
            if ($product) {
                $subtotal += $product->price * $cartDetail->qty;
            }
        }
        $pajak = $subtotal * 0.02;

        $order = Orders::create([
            'user_id' => Auth::id(),
            'partner_id' => $request->partner_id,
            'status' => 'pending',
            'subtotal' => $subtotal,
            'grand_total' => $subtotal + $pajak,
            'payment' => $request->payment,
            'bank' => $request->bank,
            'expired' => now()->addDays(1),
        ]);

        $carts->cartDetail()->delete();

        return response()->json(['message' => 'Pesanan berhasil dibuat', 'order' => $order]);
    }
}
