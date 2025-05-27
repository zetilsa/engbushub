<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Awal query produk
        $query = Product::with([
            'user:id,city_id',
            'user.city.province:id,name',
        ])->latest();

        // Filter berdasarkan kategori jika ada
        if ($request->has('category_id') && $request->category_id) {
            $categoryIds = explode(',', $request->category_id);
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
            $query->whereHas('user.city.province', function ($q) use ($request) {
                $q->where('id', $request->province_id);
            });
        }

        // Filter berdasarkan kota jika ada
        if ($request->has('city_id') && $request->city_id) {
            $query->whereHas('user.city', function ($q) use ($request) {
                $q->where('id', $request->city_id);
            });
        }

        // Ambil produk dan transformasikan agar hanya mengirim data minimal
        $products = $query->get()->map(function ($product) {
            $images = json_decode($product->image, true);
            return [
                'id'       => $product->id,
                'name'     => $product->name,
                'slug'     => $product->slug,
                'price'    => $product->price,
                'image'    => ! empty($images) ? asset('storage/' . $images[0]) : null,
                'province' => optional($product->user->city->province)->name,
                'city'     => optional($product->user->city)->name,
            ];
        });

        return response()->json([
            'products' => $products,
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
        $product = Product::with(['user.city.province', 'category:id,name'])->where('slug', $slug)->first();

        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'product' => [
                'id'          => $product->id,
                'name'        => $product->name,
                'slug'        => $product->slug,
                'price'       => $product->price,
                'description' => $product->description,
                'image'       => json_decode($product->image),
                'category'    => [
                    'id'   => $product->category->id,
                    'name' => $product->category->name,
                ],
                'user'        => [
                    'id'       => $product->user->id,
                    'name'     => $product->user->name,
                    'city'     => optional($product->user->city)->name,
                    'province' => optional($product->user->city->province)->name,
                ],
            ],
        ]);
    }

    // Menyimpan produk (hanya bisa dilakukan oleh user yang login)
    public function store(Request $request)
    {
        // Validasi request
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|integer|min:0',
            'image.*'     => 'required|image|mimes:jpg,jpeg,png|max:2048', // Maks 2MB per file
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle Upload Gambar
        $images = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $path     = $file->store('products', 'public');
                $images[] = $path;
            }
        }

        $user = Auth::user();

        $slug         = Str::slug($request->name);
        $originalSlug = $slug;
        $count        = 1;

// Cek apakah slug sudah ada di database
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Buat produk
        $product = Product::create([
            'user_id'     => $user->id,
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'price'       => $request->price,
            'image'       => json_encode($images),
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }

    public function destroy($id)
    {
        $user    = Auth::user();
        $product = Product::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (! $product) {
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
            'message' => 'Product deleted successfully',
        ]);
    }

}
