<?php

namespace App\Http\Controllers;

use App\Models\{Brand, Category, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::with(['category', 'brand'])->latest();

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('category.name', fn($row) => $row->category->name ?? '—')
                ->addColumn('brand.name', fn($row) => $row->brand->name ?? '—')
                ->addColumn('image', fn($row) => $row->image)
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-info edit-btn" data-id="' . $row->id . '">
                            <i class="fas fa-edit"></i>Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">
                            <i class="fas fa-trash-alt"></i>Delete
                        </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.index', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:products,name',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric',
            'compare_price' => 'nullable|numeric',
            'stock' => 'nullable|integer|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->compare_price && $request->compare_price <= $request->price) {
            return response()->json([
                'errors' => ['compare_price' => ['Compare price must be greater than actual price']]
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/products', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'stock' => $request->stock,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        $product->load(['category', 'brand']);
        $product->price = (float) $product->price;

        return response()->json(['message' => 'Product added successfully!', 'product' => $product]);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'brand'])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $product->price = (float) $product->price;
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric',
            'compare_price' => 'nullable|numeric',
            'stock' => 'nullable|integer|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->compare_price && $request->compare_price <= $request->price) {
            return response()->json([
                'errors' => ['compare_price' => ['Compare price must be greater than actual price']]
            ], 422);
        }

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('uploads/products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'stock' => $request->stock,
            'short_description' => $request->short_description,
            'description' => $request->description,
        ]);

        $product->load(['category', 'brand']);
        $product->price = (float) $product->price;

        return response()->json(['message' => 'Product updated successfully!', 'product' => $product]);
    }

    public function destroy(Product $product)
    {
        try {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            return response()->json(['message' => 'Product deleted successfully!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Cannot delete product. It is associated with other records.'
            ], 409);
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => 'No products selected.'], 400);
        }

        $products = Product::whereIn('id', $ids)->get();

        foreach ($products as $product) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();
        }

        return response()->json(['message' => 'Selected products deleted successfully.']);
    }
}
