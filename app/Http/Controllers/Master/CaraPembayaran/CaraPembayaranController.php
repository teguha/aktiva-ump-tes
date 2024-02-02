<?php

namespace App\Http\Controllers\Master\CaraPembayaran;

use App\Http\Controllers\Controller;
use App\Models\Master\CaraPembayaran\CaraPembayaran;
use Illuminate\Http\Request;
use App\Exports\GenerateExport;
use App\Http\Requests\Master\CaraPembayaran\CaraPembayaranRequest;

class CaraPembayaranController extends Controller
{
    protected $module = 'master_cara_pembayaran';
    protected $routes = 'master.cara_pembayaran';
    protected $views  = 'master.cara_pembayaran';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms.'.view',
            'title' => 'Cara Pembayaran',
            'breadcrumb' => [
                'Data Master' => route($this->routes . '.index'),
                'Cara Pembayaran' => route($this->routes.'.index'),
            ]
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:nama|label:Nama|className:text-left'),
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
        $records = CaraPembayaran::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('nama', function ($record) use ($user) {
                return '<span class="badge badge-danger">' . $record->nama . '</span>';
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
                        'attrs' => 'data-confirm-text="'.__('Hapus').' '.$record->nama.'?"',
                    ];
                }
                return $this->makeButtonDropdown($actions);
            })
            ->rawColumns(['nama','action','updated_by','location'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views.'.create');
    }

    public function store(CaraPembayaranRequest $request)
    {
        $record = new CaraPembayaran;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(CaraPembayaran $record)
    {
        return $this->render($this->views.'.show', ['record' => $record]);
    }

    public function edit(CaraPembayaran $record)
    {
        return $this->render($this->views.'.edit', ['record' => $record]);
    }

    public function update(CaraPembayaranRequest $request, CaraPembayaran $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(CaraPembayaran $record)
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

        $record = new CaraPembayaran;
        return $record->handleImport($request);
    }
}
