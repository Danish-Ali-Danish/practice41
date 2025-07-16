<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('user.home');
    }

    public function allProducts(Request $request)
    {
        $query = Product::with('brand', 'category');

        if ($request->has('category')) {
            $query->whereIn('category_id', $request->category);
        }

        if ($request->has('brand')) {
            $query->whereIn('brand_id', $request->brand);
        }

        if ($request->has('filter') && $request->filter === 'featured') {
            $query->where('is_featured', true);
        }

        $products = $query->paginate(20);

        $popularBrands = Brand::withCount('products')
            ->has('products')
            ->orderByDesc('products_count')
            ->take(6)
            ->get();

        $categories = Category::withCount('products')->get();

        // Filter brands that have products in selected categories
        if ($request->has('category')) {
            $brands = Brand::whereHas('products', function ($q) use ($request) {
                $q->whereIn('category_id', $request->category);
            })->get();
            $brandMessage = null;
        } else {
            // Fallback popular brands if no category selected
            $brands = Brand::has('products')->inRandomOrder()->take(10)->get();
            $brandMessage = 'Showing popular brands. Select a category to filter more brands.';
        }

        // If AJAX call is for brand filters only (no full reload)
        if ($request->ajax() && $request->has('brand_only')) {
            return view('user.includes.partial-brand-filter', compact('brands', 'brandMessage'))->render();
        }

        return view('user.allproducts', compact(
            'products',
            'categories',
            'brands',
            'brandMessage',
            'popularBrands'
        ));
    }

    public function productDetails($id)
    {
        $product = Product::with('reviews')->findOrFail($id);
        $categories = Category::withCount('products')->get();
        $Products = Product::latest()->take(5)->get();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(10)
            ->get();

        return view('user.product-details', compact(
            'product',
            'categories',
            'Products',
            'relatedProducts'
        ));
    }

    public function cart()
    {
        return view('user.cart');
    }

    public function checkout()
    {
        return view('user.checkout');
    }

    public function orders()
    {
        return view('user.orders');
    }

    public function wishlist()
    {
        return view('user.wishlist');
    }
}
