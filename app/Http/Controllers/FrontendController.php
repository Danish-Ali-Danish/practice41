<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;  // Add at the top with Category
use App\Models\Category;
use App\Models\Product;

class FrontendController extends Controller
{
    public function allCate()
    {
        $categories = Category::all();
        return view('user.categories.index', compact('categories'));
    }

    public function preview($id)
    {
        $category = Category::findOrFail($id);

        $imagePath = $category->file_path
            ? asset('storage/' . $category->file_path)
            : asset('images/default-category.png');

        return response()->json([
            'image' => $imagePath
        ]);
    }

    public function allBrands()
    {
        $brands = Brand::all();
        return view('user.brands.index', compact('brands'));
    }

    public function previewBrand($id)
    {
        $brand = Brand::findOrFail($id);

        $imagePath = $brand->file_path
            ? asset('storage/' . $brand->file_path)
            : asset('images/default-brand.png');

        return response()->json([
            'image' => $imagePath
        ]);
    }

    // FrontendController.php
    public function productsByCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)->paginate(12);

        return view('user.category-products', compact('category', 'products'));
    }

    public function productsByBrand($slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();
        $products = Product::where('brand_id', $brand->id)->paginate(12);

        return view('user.brand-products', compact('brand', 'products'));
    }
}
