<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BlogPost::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('image', fn($row) => $row->image
                    ? '<img src="/storage/' . $row->image . '" width="60" height="40" style="object-fit:cover;">'
                    : 'No Image')
                ->editColumn('status', fn($row) => ucfirst($row->status))
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-info edit-btn" data-id="' . $row->id . '"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" data-title="' . $row->title . '"><i class="fas fa-trash-alt"></i></button>
                    ';
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        return view('admin.blog-posts.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'date' => 'required|date',
            'content' => 'required',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blog_images', 'public');
        }

        BlogPost::create($data);
        return response()->json(['success' => true]);
    }

    public function show(BlogPost $blog_post)
    {
        return response()->json($blog_post);
    }

    public function update(Request $request, BlogPost $blog_post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'date' => 'required|date',
            'content' => 'required',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blog_images', 'public');
        }

        $blog_post->update($data);
        return response()->json(['success' => true]);
    }

    public function destroy(BlogPost $blog_post)
    {
        $blog_post->delete();
        return response()->json(['success' => true]);
    }
}
