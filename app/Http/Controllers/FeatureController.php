<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class FeatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $title = 'Feature';
        $feature = DB::table('features')->select('id', 'name', 'output', 'description', 'duration')->get();
        if ($request->ajax()) {
            return DataTables::of($feature)
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('feature.edit', $row->id) . '" class="btn btn-success"><i class="fas fa-pencil-alt"></i></a>
                    <button type="button" class="btn btn-danger btn-delete" data-toggle="modal" data-target="#exampleModal" data-id="' . $row->id . '" data-name="' . $row->name . '">
                    <i class="fas fa-trash"></i>
                </button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->escapeColumns()
                ->make(true);
        }
        //dd($feature);
        return view('admin.feature.list', compact('title'));
    }
    public function create()
    {
        $title = 'Add Feature';
        return view('admin.feature.add', compact('title'));
    }
    public function store(Request $request)
    {
        Feature::create([
            'name' => $request->_name,
            'output' => $request->_output,
            'description' => $request->_description,
            'duration' => $request->_duration,
        ]);

        return redirect()->route('feature.index')->with('success', 'Data Created');;
    }
    public function edit($id)
    {
        $title = 'Edit Feature';
        $data = Feature::where('id', $id)->first();
        return view('admin.feature.edit', compact('title', 'data'));
    }
    public function update(Request $request, Feature $feature)
    {
        $feature->update([
            'name' => $request->_name,
            'output' => $request->_output,
            'description' => $request->_description,
            'duration' => $request->_duration
        ]);
        return redirect()->route('feature.index')->with('success', 'Data Updated');
    }
    public function delete(Request $request)
    {
        $delete = Feature::find($request->id);
        $delete->delete();
        return redirect()->route('feature.index')->with('warning', 'Data Deleted');
    }
}
