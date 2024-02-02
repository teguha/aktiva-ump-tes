<?php

namespace App\Http\Controllers\Master\Penggunaan;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Penggunaan\PenggunaanRequest;
use App\Models\Master\Penggunaan\Penggunaan;
use Illuminate\Http\Request;

class PenggunaanController extends Controller
{
    protected $module = 'master_penggunaan';
    protected $routes = 'master.penggunaan';
    protected $views = 'master.penggunaan';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms.'.view',
            'title' => 'Penggunaan Anggaran',
            'breadcrumb' => [
                'Data Master' => route($this->routes . '.index'),
                'Penggunaan Anggaran' => route($this->routes.'.index'),
            ]
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:name|label:Nama|className:text-left'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);
        return $this->render($this->views.'.index');
    }

    public function grid()
    {
        $user = auth()->user();
        $records = Penggunaan::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('updated_by', function ($record) {
                return $record->createdByRaw();
            })
            ->addColumn('action', function ($record) use ($user) {
                $actions = [
                    'type:show|id:'.$record->id,
                    'type:edit|id:'.$record->id,
                ];
                if ($record->canDeleted()) {
                    $actions[] = [
                        'type' => 'delete',
                        'id' => $record->id,
                        'attrs' => 'data-confirm-text="'.__('Hapus').' '.$record->name.'?"',
                    ];
                }
                return $this->makeButtonDropdown($actions);
            })
            ->rawColumns(['action','updated_by','location'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views.'.create');
    }

    public function store(PenggunaanRequest $request)
    {
        $record = new Penggunaan;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(Penggunaan $record)
    {
        return $this->render($this->views.'.show', compact('record'));
    }

    public function edit(Penggunaan $record)
    {
        return $this->render($this->views.'.edit', compact('record'));
    }

    public function update(PenggunaanRequest $request, Penggunaan $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Penggunaan $record)
    {
        return $record->handleDestroy();
    }

    public function import()
    {
        if (request()->get('download') == 'template') {
            return $this->template();
        }
        return $this->render($this->views.'.import');
    }

    public function template()
    {
        $fileName = date('Y-m-d').' Template Import Data '. $this->prepared('title') .'.xlsx';
        $view = $this->views.'.template';
        $data = [];
        return \Excel::download(new GenerateExport($view, $data), $fileName);
    }

    public function importSave(Request $request)
    {
        $request->validate([
            'uploads.uploaded' => 'required',
            'uploads.temp_files_ids.*' => 'required',
        ],[],[
            'uploads.uploaded' => 'File',
            'uploads.temp_files_ids.*' => 'File',
        ]);
        
        $record = new Penggunaan;
        return $record->handleImport($request);
    }
}
