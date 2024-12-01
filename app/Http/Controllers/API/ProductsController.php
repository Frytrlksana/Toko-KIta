<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Products;

class ProductsController extends Controller
{
    public function getProduct()
    {
        $products = Products::select(
            'products.id',
            'category_id',
            'products.name as nama_makanan',
            'categories.name as nama_category',
            'price',
            'desc'
        )
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->get();

        // Cek apakah ada produk
        if ($products->isEmpty()) {
            return response()->json(["message" => "Belum ada produk yang tersedia"], 200);
        }

        // Jika ada produk, kembalikan data produk
        return response()->json(["products" => $products], 200);
    }



    public function detailProduct($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        return response()->json(['product' => $product, 'message' => 'Data product berhasil diambil'], 200);
    }

    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required',
            'desc' => 'required',
            'image' => 'required',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Validasi data nama atau harga salah'], 400);
        }

        $data = $request->all();
        $product = Products::create($data);

        return response()->json(['products' => $product, 'message' => 'Data product berhasil ditambahkan'], 200);
    }

    public function updateProduct(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required',
            'image' => 'required',
            'price' => 'required',
            'desc' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Validasi data nama atau harga salah'], 400);
        }

        $product = Products::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'image' => $request->image,
            'price' => $request->price,
            'desc' => $request->desc,
        ]);

        return response()->json(['product' => $product, 'message' => 'Data product berhasil diubah'], 200);
    }
    public function deleteProduct($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Data product berhasil dihapus'], 200);
    }
}
