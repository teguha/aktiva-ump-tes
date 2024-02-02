<?php

namespace App\Http\Controllers\Master\SkemaPembayaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\GenerateExport;
use App\Http\Requests\Master\SkemaPembayaran\SkemaPembayaranRequest;
use App\Models\Master\SkemaPembayaran\SkemaPembayaran;

class SkemaPembayaranController extends Controller
{
    protected $module = 'master_skema_pembayaran';
    protected $routes = 'master.skema_pembayaran';
    protected $views  = 'master.skema_pembayaran';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms.'.view',
            'title' => 'Skema Pembayaran',
            'breadcrumb' => [
                'Data Master' => route($this->routes . '.index'),
                'Skema Pembayaran' => route($this->routes.'.index'),
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
        $records = SkemaPembayaran::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('name', function ($record) use ($user) {
                return '<span class="badge badge-danger">' . $record->name . '</span>';
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
            ->rawColumns(['name','action','updated_by','location'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views.'.create');
    }

    public function store(SkemaPembayaranRequest $request)
    {
        $record = new SkemaPembayaran;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(SkemaPembayaran $record)
    {
        return $this->render($this->views.'.show', compact('record'));
    }

    public function edit(SkemaPembayaran $record)
    {
        return $this->render($this->views.'.edit', compact('record'));
    }

    public function update(SkemaPembayaranRequest $request, SkemaPembayaran $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(SkemaPembayaran $record)
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

        $record = new SkemaPembayaran;
        return $record->handleImport($request);
    }
}
