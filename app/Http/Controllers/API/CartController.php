<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CartController extends Controller
{
    public function getCart()
    {
        $user_id = Auth::user()->id;
        $cartItems = Cart::where('user_id', $user_id)->first();

        if (!$cartItems) {
            return response()->json(['error' => 'Keranjang tidak ditemukan'], 404);
        }

        $cartDetails = CartDetail::where('cart_id', $cartItems->id)->get();
        $result = [
            'carts' => $cartDetails,
            'user_id' => $user_id
        ];

        return response()->json($result, 200);
    }

    public function addCart(Request $request)
    {
        $user = $request->user();
        $cart = Cart::create([
            'user_id' => $user->id,
        ]);

        return response()->json(['cart' => $cart, 'message' => 'Item berhasil ditambahkan ke keranjang'], 200);
    }

    public function addCartDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|exists:carts,id',
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Validasi gagal'], 400);
        }

        $cart = Cart::where('id', $request->cart_id)->where('user_id', Auth::id())->first();

        if (!$cart) {
            return response()->json(['message' => 'Keranjang tidak ditemukan'], 404);
        }

        $cartDetail = CartDetail::create([
            'cart_id' => $request->cart_id,
            'product_id' => $request->product_id,
            'qty' => $request->qty,
        ]);

        return response()->json(['cartDetail' => $cartDetail, 'message' => 'Item berhasil ditambahkan ke keranjang'], 200);
    }

    public function editCartQuantity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|exists:carts,id',
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Validasi gagal'], 400);
        }

        $cartDetail = CartDetail::where('cart_id', $request->cart_id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$cartDetail) {
            return response()->json(['message' => 'Item tidak ditemukan di keranjang'], 404);
        }

        $cartDetail->qty = $request->qty;
        $cartDetail->save();

        return response()->json(['cartDetail' => $cartDetail, 'message' => 'Jumlah produk dalam keranjang berhasil diubah'], 200);
    }


    public function deleteCart($id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json(['message' => 'Item tidak ditemukan di keranjang'], 404);
        }

        $cart->delete();

        return response()->json(['message' => 'Item berhasil dihapus dari keranjang'], 200);
    }

}
