<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('file', function ($row) {
                    if ($row->file_path) {
                        $url = asset('storage/' . $row->file_path);
                        return '<img src="' . $url . '" width="50" height="50" style="object-fit:cover;cursor:pointer" class="file-preview" data-src="' . $url . '">';
                    } else {
                        return 'No File';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-info edit-btn" data-id="' . $row->id . '"><i class="fas fa-edit"></i> Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '"><i class="fas fa-trash-alt"></i> Delete</button>
                    ';
                })
                ->rawColumns(['file', 'action'])
                ->make(true);
        }

        return view('admin.categories.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $filePath = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('uploads/categories', 'public');
        }

        $category = Category::create([
            'name' => $request->name,
            'file_path' => $filePath
        ]);

        return response()->json(['message' => 'Category added successfully!', 'category' => $category]);
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('file')) {
            if ($category->file_path && Storage::disk('public')->exists($category->file_path)) {
                Storage::disk('public')->delete($category->file_path);
            }

            $category->file_path = $request->file('file')->store('uploads/categories', 'public');
        }

        $category->name = $request->name;
        $category->save();

        return response()->json(['message' => 'Category updated successfully!', 'category' => $category]);
    }

    public function destroy(Category $category)
    {
        try {
            if ($category->file_path && Storage::disk('public')->exists($category->file_path)) {
                Storage::disk('public')->delete($category->file_path);
            }

            $category->delete();
            return response()->json(['message' => 'Category deleted successfully!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Cannot delete category. It is associated with other records.'
            ], 409);
        }
    }
}
