<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Product;
use App\Models\Promo;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->take(10)->get();
        $brands = Brand::latest()->take(10)->get();
        $products = Product::latest()->take(12)->get();  // Latest 12 Products

        $features = Feature::latest()->take(4)->get();  // Home Features
        $promos = Promo::latest()->take(3)->get();  // Promotional banners
        $testimonials = Testimonial::latest()->take(4)->get();  // Client reviews
        $blogPosts = BlogPost::latest()->take(3)->get();

        return view('user.home', compact(
            'categories',
            'brands',
            'products',
            'features',
            'promos',
            'testimonials',
            'blogPosts'
        ));
    }
}
