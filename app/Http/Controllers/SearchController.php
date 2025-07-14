<?php
namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function suggest(Request $request)
    {
        $query = $request->query('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [];

        $products = Product::where('name', 'LIKE', "%$query%")->limit(5)->get();
        $categories = Category::where('name', 'LIKE', "%$query%")->limit(3)->get();
        $brands = Brand::where('name', 'LIKE', "%$query%")->limit(3)->get();

        foreach ($products as $product) {
            $results[] = [
                'name' => $product->name,
                'url' => url('/product/' . $product->slug),  // or use route() if you have one
                'type' => 'Product',
            ];
        }

        foreach ($categories as $category) {
            $results[] = [
                'name' => $category->name,
                'url' => url('/category/' . $category->slug),
                'type' => 'Category',
            ];
        }

        foreach ($brands as $brand) {
            $results[] = [
                'name' => $brand->name,
                'url' => url('/brand/' . $brand->slug),
                'type' => 'Brand',
            ];
        }

        return response()->json($results);
    }
}
