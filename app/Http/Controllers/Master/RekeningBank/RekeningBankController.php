<?php

namespace App\Http\Controllers\Master\RekeningBank;

use App\Http\Controllers\Controller;
use App\Models\Master\RekeningBank\RekeningBank;
use Illuminate\Http\Request;
use App\Exports\GenerateExport;
use App\Http\Requests\Master\RekeningBank\RekeningBankRequest;

class RekeningBankController extends Controller
{
    protected $module = 'master_rekening_bank';
    protected $routes = 'master.rekening_bank';
    protected $views  = 'master.rekening_bank';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms.'.view',
            'title' => 'Rekening Bank',
            'breadcrumb' => [
                'Data Master' => route($this->routes . '.index'),
                'Rekening Bank' => route($this->routes.'.index'),
            ]
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:no_rekening|label:No Rekening|className:text-center'),
                    $this->makeColumn('name:pemilik|label:Nama Pemilik|className:text-center'),
                    $this->makeColumn('name:kcp|label:KCP|className:text-center'),
                    $this->makeColumn('name:bank|label:Bank'),
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
        $records = RekeningBank::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('pemilik', function ($record) {
                return $record->pemilik->name ?? null;
            })
            ->addColumn('no_rekening', function ($record) {
                return $record->no_rekening  ?? null;
            })
            ->addColumn('kcp', function ($record) {
                return $record->kcp  ?? null;
            })
            ->addColumn('bank', function ($record) {
                return $record->bank->name   ?? null;
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
            ->rawColumns(['bank','pemilik','no_rekening','kcp','action','updated_by','location'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views.'.create');
    }

    public function store(RekeningBankRequest $request)
    {
        $record = new RekeningBank;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(RekeningBank $record)
    {
        return $this->render($this->views.'.show', ['record' => $record]);
    }

    public function edit(RekeningBank $record)
    {
        return $this->render($this->views.'.edit', ['record' => $record]);
    }

    public function update(RekeningBankRequest $request, RekeningBank $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(RekeningBank $record)
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

        $record = new RekeningBank;
        return $record->handleImport($request);
    }
}
