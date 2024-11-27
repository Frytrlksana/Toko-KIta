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
        // Mengambil semua data produk dengan kolom tertentu
        $products = Products::select(
            'products.id',
            'products.name as nama_makanan',
            'products.image as gambar',
            'products.desc as deskripsi',
            'products.price as harga'
        )->get();

        // Mengembalikan data produk
        return $products;
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
