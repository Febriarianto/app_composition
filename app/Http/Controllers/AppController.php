<?php

namespace App\Http\Controllers;

use App\Models\App;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class AppController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $title = 'Application';
        $app = DB::table('apps')->select('id', 'name', 'description')->get();
        if ($request->ajax()) {
            return DataTables::of($app)
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('app.edit', $row->id) . '" class="btn btn-success"><i class="fas fa-pencil-alt"></i></a>
                    <button type="button" class="btn btn-danger btn-delete" data-toggle="modal" data-target="#exampleModal" data-id="' . $row->id . '" data-name="' . $row->name . '">
                    <i class="fas fa-trash"></i>
                </button>
                <a href="' . route('app.show', $row->id) . '" class="btn btn-info btnprn"><i class="fas fa-eye"></i></a>';
                    return $btn;
                })
                ->addColumn('module', function ($row) {
                    $module = DB::table('app_modules')->join('modules', 'modules.id', 'app_modules.id_module')->select('modules.name')->where('app_modules.id_app', $row->id)->get();
                    $text = "";
                    foreach ($module as $key) {
                        $text .= "<span class='badge bg-info text-dark me-2'>" . $key->name . "</span>";
                    }
                    return $text;
                })
                ->rawColumns(['action', 'module'])
                ->escapeColumns()
                ->make(true);
        }
        return view('admin.app.list', compact('title'));
    }
    public function create()
    {
        $title = 'Add Application';
        return view('admin.app.add', compact('title'));
    }
    public function store(Request $request)
    {
        $app = App::create([
            'name' => $request->_name,
            'description' => $request->_description
        ]);
        foreach ($request->chkmodule as $m) {
            DB::table('app_modules')->insert([
                'id_app' => $app->id,
                'id_module' => $m,
            ]);
        }
        return redirect()->route('app.index')->with('success', 'Data Created');
    }
    public function edit($id)
    {
        $title = 'Edit Application';
        $data = App::find($id);
        $chkmodule = DB::table('app_modules')
            ->join('modules', 'modules.id', '=', 'app_modules.id_module')
            ->select('app_modules.id_module as id', 'modules.name as name')
            ->where('app_modules.id_app', $id)
            ->get();

        return view('admin.app.edit', compact('title', 'data', 'chkmodule'));
    }
    public function update(Request $request, App $app)
    {
        $app->update([
            'name' => $request->_name,
            'description' => $request->_description
        ]);

        if ($request->chkmodule > 0) {
            DB::table('app_modules')->where('id_app', $app->id)->delete();
            foreach ($request->chkmodule as $m) {
                DB::table('app_modules')->insert([
                    'id_app' => $app->id,
                    'id_module' => $m
                ]);
            }
        } else {
            DB::table('app_modules')->where('id_app', $app->id)->delete();
        }

        return redirect()->route('app.index')->with('success', 'Data Updated');
    }
    public function delete(Request $request)
    {
        App::find($request->id)->delete();
        DB::table('app_modules')->where('id_app', $request->id)->delete();
        return redirect()->route('app.index')->with('warning', 'Data Deleted');
    }
    public function print($id)
    {
        ini_set('max_execution_time', 300);
        $data = DB::table('apps')
            ->select('apps.id', 'apps.name', 'apps.description')
            ->where('apps.id', $id)
            ->first();
        $data_module = DB::table('apps')
            ->select('modules.name', 'modules.description')
            ->join('app_modules', 'app_modules.id_app', '=', 'apps.id')
            ->join('modules', 'app_modules.id_module', '=', 'modules.id')
            ->where('apps.id', $id)
            ->get();
        $data_feature = DB::table('apps')
            ->select('modules.name as module', 'features.name as feature', 'features.output', 'features.description')
            ->join('app_modules', 'app_modules.id_app', '=', 'apps.id')
            ->join('modules', 'app_modules.id_module', '=', 'modules.id')
            ->join('module_features', 'module_features.id_module', '=', 'modules.id')
            ->join('features', 'features.id', '=', 'module_features.id_feature')
            ->where('apps.id', $id)
            ->get();
        return view('admin.app.print', compact('data', 'data_module', 'data_feature'));
    }
    public function select(Request $request)
    {
        $page = $request->page;
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $data = Module::where('name', 'LIKE', '%' . $request->q . '%')
            ->orderBy('name')
            ->skip($offset)
            ->take($resultCount)
            ->selectRaw('id as id,name as text')
            ->get();
        $count = Module::where('name', 'LIKE', '%' . $request->q . '%')
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
    public function show($id)
    {
        $title = 'Detail Application';
        $print = $id;
        $data = DB::table('apps')
            ->select('apps.id', 'apps.name')
            ->where('apps.id', $id)
            ->first();
        $data_module = DB::table('apps')
            ->select('modules.name', DB::raw('round(SUM(features.duration)/60) as duration '))
            ->leftJoin('app_modules', 'app_modules.id_app', '=', 'apps.id')
            ->leftJoin('modules', 'app_modules.id_module', '=', 'modules.id')
            ->leftJoin('module_features', 'module_features.id_module', '=', 'modules.id')
            ->leftJoin('features', 'features.id', '=', 'module_features.id_feature')
            ->groupBy('modules.id')
            ->where('apps.id', $id)
            ->get();
        $data_feature = DB::table('apps')
            ->select('modules.name as module', 'features.name as feature')
            ->join('app_modules', 'app_modules.id_app', '=', 'apps.id')
            ->join('modules', 'app_modules.id_module', '=', 'modules.id')
            ->join('module_features', 'module_features.id_module', '=', 'modules.id')
            ->join('features', 'features.id', '=', 'module_features.id_feature')
            ->where('apps.id', $id)
            ->get();
        $total = 0;
        foreach ($data_module as $t) {
            $total += $t->duration;
        }
        //dd($data_module, $data_feature, $total, $title);
        return view('admin.app.preview', compact('title', 'data', 'data_module', 'data_feature', 'total', 'print'));
    }
}
