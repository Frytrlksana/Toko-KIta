<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailOrders;
use App\Models\Orders;
use Illuminate\Http\Request;
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
}
