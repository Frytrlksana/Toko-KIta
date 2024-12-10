<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailOrders;
use App\Models\Orders;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    public function index()
    {
        $Orders = Orders::join('users', 'users_id', '=', 'users.id')
            ->select('Orders.*', 'users.name as nama', 'users.telepon as telp')
            ->get();

        $OrdersLunas = Orders::join('users', 'users_id', '=', 'users.id')
            ->select('Orders.*', 'users.name as nama', 'users.telepon as telp')
            ->where('Orders.status', '=', 'lunas')
            ->get();

        return response()->json([
            'Orders' => $Orders,
            'OrdersLunas' => $OrdersLunas,
        ], 200);
    }

    public function show($id)
    {
        $Orders = Orders::join('users', 'users_id', '=', 'users.id')
            ->select('Orders.*', 'users.name as nama', 'users.telepon as telp', 'users.email as email')
            ->where('Orders.id', $id)
            ->first();

        if (!$Orders) {
            return response()->json(['error' => 'Orders not found'], 404);
        }

        $detail = DetailOrders::join('Orders', 'Orders_id', '=', 'Orders.id')
            ->join('products', 'products_id', '=', 'products.id')
            ->select('detail_Orders.*', 'Orders.status as status', 'Orders.total as total', 'products.nama_product as nama_product', 'products.deskripsi as deskripsi', 'products.foto as foto')
            ->where('detail_Orders.Orders_id', $id)
            ->get();

        return response()->json(['Orders' => $Orders, 'detail' => $detail], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:lunas,tidak',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $Orders = Orders::find($id);

        if (!$Orders) {
            return response()->json(['error' => 'Orders not found'], 404);
        }

        $Orders->status = $request->status;
        $Orders->save();

        return response()->json(['message' => 'Orders updated successfully'], 200);
    }

    public function checkout(Request $request)
    {
        try {
            // Mulai Orders
            DB::beginTransaction();

            // Validasi input
            $request->validate([
                'no_telepon' => 'required|string|max:15',
                'total' => 'required|numeric',
            ]);

            // Buat Orders baru
            $Orders = new Orders();
            $Orders->users_id = auth()->user()->id; // Pastikan ini kolom yang benar
            $Orders->no_telepon = $request->no_telepon;
            $Orders->total = $request->total; // Pastikan ini sesuai dengan perhitungan yang benar
            $Orders->save();

            // Ambil data Cart untuk user yang sedang login
            $Cart = Cart::where('user_id', auth()->user()->id)->get();

            foreach ($Cart as $cart) {
                // Buat detail Orders
                $detail_Orders = new DetailOrders();
                $detail_Orders->Orders_id = $Orders->id;
                $detail_Orders->produk_id = $cart->produk_id;
                $detail_Orders->quantity = $cart->quantity;
                $detail_Orders->harga = $cart->quantity * $cart->harga;
                $detail_Orders->save();
            }

            // Hapus data Cart setelah Orders berhasil
            Cart::where('user_id', auth()->user()->id)->delete();

            // Commit Orders
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Orders berhasil',
                'transaction_id' => $Orders->id,
            ]);
        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollBack();

            Log::error('Checkout Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat melakukan Orders: ' . $e->getMessage(),
            ]);
        }
    }

    public function coba($id_Orders)
    {
        return view('coba');
    }

}
