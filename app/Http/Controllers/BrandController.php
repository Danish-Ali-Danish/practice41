<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Brand::with('category')->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category.name', function ($row) {
                    return $row->category->name ?? '—';
                })
                ->addColumn('file_path', function ($row) {
                    if ($row->file_path) {
                        $url = asset('storage/' . $row->file_path);
                        return '<img src="' . $url . '" width="50" height="50" style="object-fit:cover;cursor:pointer" class="file-preview" data-src="' . $url . '">';
                    } else {
                        return 'No File';
                    }
                })
                ->addColumn('action', function ($row): string {
                    return '
                        <button class="btn btn-sm btn-info edit-btn" data-id="' . $row->id . '">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    ';
                })
                ->rawColumns(['file_path', 'action', 'category.name'])
                ->make(true);
        }

        $categories = Category::all();
        return view('admin.brands.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name',
            'category_id' => 'required|exists:categories,id',
            'file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $filePath = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('uploads/brands', 'public');
        }

        $brand = Brand::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'file_path' => $filePath,
        ]);

        return response()->json(['message' => 'Brand added successfully!', 'brand' => $brand]);
    }

    public function show($id)
    {
        $brand = Brand::with('category')->findOrFail($id);
        return response()->json($brand);
    }

    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'category_id' => 'required|exists:categories,id',
            'file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('file')) {
            if ($brand->file_path && Storage::disk('public')->exists($brand->file_path)) {
                Storage::disk('public')->delete($brand->file_path);
            }

            $brand->file_path = $request->file('file')->store('uploads/brands', 'public');
        }

        $brand->name = $request->name;
        $brand->category_id = $request->category_id;
        $brand->save();

        return response()->json(['message' => 'Brand updated successfully!', 'brand' => $brand]);
    }

    public function destroy(Brand $brand)
    {
        try {
            if ($brand->file_path && Storage::disk('public')->exists($brand->file_path)) {
                Storage::disk('public')->delete($brand->file_path);
            }

            $brand->delete();
            return response()->json(['message' => 'Brand deleted successfully!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Cannot delete brand. It is associated with other records.'
            ], 409);
        }
    }

    public function savePopular(Request $request)
    {
        // Validate input
        $brandIds = $request->input('brand_ids', []);

        if (!is_array($brandIds)) {
            return response()->json(['message' => 'Invalid data format.'], 422);
        }

        // Reset all to false
        Brand::query()->update(['is_popular' => false]);

        // Set selected to true
        if (count($brandIds) > 0) {
            Brand::whereIn('id', $brandIds)->update(['is_popular' => true]);
        }

        return response()->json(['message' => 'Popular brands updated.']);
    }
}
