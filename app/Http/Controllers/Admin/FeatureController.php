<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FeatureController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $features = Feature::select(['id', 'title', 'icon', 'description']);

            return DataTables::of($features)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('admin.features.partials.actions', compact('row'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.features.index');
    }

    public function create()
    {
        return view('admin.features.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'icon' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        Feature::create($request->all());
        return redirect()->route('features.index')->with('success', 'Feature created successfully.');
    }

    public function edit(Feature $feature)
    {
        return view('admin.features.edit', compact('feature'));
    }

    public function update(Request $request, Feature $feature)
    {
        $request->validate([
            'icon' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        $feature->update($request->all());
        return redirect()->route('features.index')->with('success', 'Feature updated.');
    }

    public function destroy(Feature $feature)
    {
        $feature->delete();
        return back()->with('success', 'Feature deleted.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');  // expects an array of IDs

        if (is_array($ids)) {
            Feature::whereIn('id', $ids)->delete();
            return response()->json(['message' => 'Selected features deleted successfully.']);
        }

        return response()->json(['message' => 'No features selected.'], 400);
    }
}
