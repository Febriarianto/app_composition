<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $title = 'Module';
        $module = DB::table('modules')->select('id', 'name', 'description')->get();
        if ($request->ajax()) {
            return DataTables::of($module)
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('module.edit', $row->id) . '" class="btn btn-success"><i class="fas fa-pencil-alt"></i></a>
                    <button type="button" class="btn btn-danger btn-delete" data-toggle="modal" data-target="#exampleModal" data-id="' . $row->id . '" data-name="' . $row->name . '">
                    <i class="fas fa-trash"></i>
                </button>';
                    return $btn;
                })
                ->addColumn('feature', function ($row) {
                    $feature = DB::table('module_features')->join('features', 'features.id', 'module_features.id_feature')->select('features.name')->where('module_features.id_module', $row->id)->get();
                    $text = "";
                    foreach ($feature as $key) {
                        # code...
                        $text .= "<span class='badge bg-info text-dark me-2'>" . $key->name . "</span>";
                    }
                    return $text;
                })
                ->rawColumns(['action', 'feature'])
                ->escapeColumns()
                ->make(true);
        }
        return view('admin.module.list', compact('title'));
    }
    public function create()
    {
        $title = 'Add Module';
        return view('admin.module.add', compact('title'));
    }
    public function store(Request $request)
    {
        $modul = Module::create([
            'name' => $request->_name,
            'description' => $request->_description
        ]);
        $modul->id;

        foreach ($request->chkfeature as $f) {
            DB::table('module_features')->insert(
                [
                    'id_feature' => $f,
                    'id_module' => $modul->id
                ]
            );
        }

        return redirect()->route('module.index')->with('success', 'Data Created !');
    }
    public function edit($id)
    {
        $title = 'Edit Module';
        $data = Module::find($id);
        $chkfeature = DB::table('module_features')
            ->join('features', 'features.id', '=', 'module_features.id_feature')
            ->select('id_feature as id', 'features.name as name')
            ->where('id_module', $id)
            ->get();
        //dd($chkfeature);
        return view('admin.module.edit', compact('title', 'data', 'chkfeature'));
    }
    public function update(Request $request, Module $module)
    {
        // Update module
        $module->update([
            'name' => $request->_name,
            'description' => $request->_description
        ]);
        //update relasi
        if ($request->chkfeature > 0) {
            DB::table('module_features')->where('id_module', $module->id)->delete();
            foreach ($request->chkfeature as $f) {
                DB::table('module_features')->insert([
                    'id_module' => $module->id,
                    'id_feature' => $f
                ]);
            }
            # code...
        } else {
            DB::table('module_features')->where('id_module', $module->id)->delete();
        }
        return redirect()->route('module.index')->with('success', 'Data Updated !');
    }
    public function delete(Request $request)
    {
        Module::find($request->id)->delete();
        DB::table('module_features')->where('id_module', $request->id)->delete();
        return redirect()->route('module.index')->with('warning', 'Data Deleted');
    }
    public function select(Request $request)
    {
        $page = $request->page;
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $data = Feature::where('name', 'LIKE', '%' . $request->q . '%')
            ->orderBy('name')
            ->skip($offset)
            ->take($resultCount)
            ->selectRaw('id as id , name as text')
            ->get();
        $count = Feature::where('name', 'LIKE', '%' . $request->q . '%')
            ->get()
            ->count();
        $endCount = $offset + $resultCount;
        $morePage = $count > $endCount;
        $results = array(
            "results" => $data,
            "pagination" => array(
                "more" => $morePage
            )
        );
        return response()->json($results);
    }
}
