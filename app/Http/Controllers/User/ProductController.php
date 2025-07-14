<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Category filter
        if ($request->has('category_id')) {
            $query->where(column: 'category_id', operator: $request->category_id);
        }

        // Price range filter
        if ($request->has('price_range')) {
            [$min, $max] = explode('-', $request->price_range);
            $query->whereBetween('price', [$min, $max]);
        }

        return response()->json([
            'products' => $query->with('category')->get()
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'product' => Product::with(['category', 'reviews'])->findOrFail($id)
        ]);
    }

    public function featured()
    {
        return response()->json([
            'products' => Product::where('is_featured', true)
                ->with('category')
                ->limit(6)
                ->get()
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        return response()->json([
            'products' => Product::where('name', 'like', "%$query%")
                ->orWhere('description', 'like', "%$query%")
                ->with('category')
                ->get()
        ]);
    }
}
