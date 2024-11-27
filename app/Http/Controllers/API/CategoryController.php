<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function getCategory()
    {
        $categories = Category::all();

        if (!$categories) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $categoriesData = [];
        foreach ($categories as $category) {
            $categoriesData[] = [
                'id' => $category->id,
                'name' => $category->name,
                'icon' => $category->icon,
            ];
        }
        return response()->json(['categories' => $categoriesData], 200);
    }

    public function detailCategory($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $products = Products::select('id', 'name', 'price', 'desc')->where('category_id', $category->id)->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'Tidak ada produk dalam kategori ini'], 200);
        }

        return response()->json(['category' => $category, 'products' => $products], 200);
    }

    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'icon' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Validasi gagal'], 400);
        }

        $category = Category::create([
            'name' => $request->name,
            'icon' => $request->icon,
        ]);

        return response()->json(['category' => $category, 'message' => 'Kategori berhasil dibuat'], 200);
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $product = Products::where('category_id', $category->id)->delete();

        $category->delete();


        return response()->json(['message' => 'Kategori berhasil dihapus'], 200);
    }
}
