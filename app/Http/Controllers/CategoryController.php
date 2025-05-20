<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function indexCategory() {
        $categories = Category::all();

        return response()->json($categories);
    }
}
