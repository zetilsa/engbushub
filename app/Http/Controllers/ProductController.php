<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
    $query = Product::with([
        'user:id,name,city_id',
        'user.city.province:id,name'
    ])->latest();

    // Filter berdasarkan kategori jika ada
    if ($request->has('category_id') && $request->category_id) {
        $categoryIds = explode(',', $request->category_id); // Menggunakan array kategori
        $query->whereIn('category_id', $categoryIds);
    }

    // Filter berdasarkan harga minimal jika ada
    if ($request->has('min_price') && $request->min_price) {
        $query->where('price', '>=', $request->min_price);
    }

    // Filter berdasarkan harga maksimal jika ada
    if ($request->has('max_price') && $request->max_price) {
        $query->where('price', '<=', $request->max_price);
    }

    // Filter berdasarkan provinsi jika ada
    if ($request->has('province_id') && $request->province_id) {
        $query->whereHas('user.city.province', function($q) use ($request) {
            $q->where('id', $request->province_id); // Memfilter berdasarkan province
        });
    }

    // Filter berdasarkan kota jika ada
    if ($request->has('city_id') && $request->city_id) {
        $query->whereHas('user.city', function($q) use ($request) {
            $q->where('id', $request->city_id); // Memfilter berdasarkan city
        });
    }

    // Ambil produk berdasarkan filter yang diterapkan
     $products = $query->get();

    // Transform gambar produk
    $products->transform(function ($product) {
        $images = json_decode($product->image, true);
       $product->image = !empty($images) 
    ? array_map(function($image) {
        return asset('storage/' . $image);
      }, $images)
    : [];
        return $product;
    });

    return response()->json([
        'products' => $products
    ]);
}


    public function myProducts()
    {
        $user = Auth::user();

        $products = Product::where('user_id', $user->id)->get();

        return response()->json($products);
    }

    // Menampilkan detail produk berdasarkan ID
    public function show($slug)
    {
        $product = Product::with(['user:id,name', 'category:id,name'])->where('slug', $slug)->first();

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'product' => $product
        ]);
    }

    // Menyimpan produk (hanya bisa dilakukan oleh user yang login)
    public function store(Request $request)
    {
        // Validasi request
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'image.*' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Maks 2MB per file
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle Upload Gambar
        $images = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $path = $file->store('products', 'public');
                $images[] = $path;
            }
        }

        $user = Auth::user();

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;

// Cek apakah slug sudah ada di database
        while (Product::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $count;
        $count++;
    }

        // Buat produk
        $product = Product::create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'image' => json_encode($images),
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

public function destroy($id)
{
    $user = Auth::user();
    $product = Product::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

    if (!$product) {
        return response()->json(['message' => 'Product not found or unauthorized'], 404);
    }

    // Hapus file gambar dari storage
    $images = json_decode($product->image, true);
    if ($images && count($images) > 0) {
        foreach ($images as $image) {
            Storage::disk('public')->delete($image);
        }
    }

    // Hapus produk dari database
    $product->delete();

    return response()->json([
        'message' => 'Product deleted successfully'
    ]);
}

}
