<?php

namespace App\Http\Controllers\Master\Org;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Org\KelompokRequest;
use App\Models\Master\Org\Kelompok;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    protected $module = 'master_org_kelompok';
    protected $routes = 'master.org.kelompok';
    protected $views = 'master.org.kelompok';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'Kelompok Jabatan',
                'breadcrumb' => [
                    'Konsol Admin' => route($this->routes . '.index'),
                    'Parameter' => route($this->routes . '.index'),
                    'Stuktur Organisasi' => route($this->routes . '.index'),
                    'Kelompok Jabatan' => route($this->routes . '.index'),
                ]
            ]
        );
    }

    public function index()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:name|label:Nama|className:text-left'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
                ],
            ]
        );
        return $this->render($this->views . '.index');
    }

    public function grid()
    {
        $user = auth()->user();
        $records = Kelompok::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'status',
                function ($record) {
                    return $record->status;
                }
            )
            ->addColumn('updated_by', function ($record) {
                return $record->createdByRaw();
            })
            ->addColumn('action', function ($record) use ($user) {
                $actions = [];
                if ($record->checkAction('show', $this->perms)) {
                    $actions[] = 'type:show|id:' . $record->id;
                }
                if ($record->checkAction('edit', $this->perms)) {
                    $actions[] = 'type:edit|id:' . $record->id;
                }
                if ($record->checkAction('delete', $this->perms)) {
                    $actions[] = [
                        'type' => 'delete',
                        'id' => $record->id,
                        'attrs' => 'data-confirm-text="' . __('Hapus parameter Kelompok Jabatan') . ' ' . $record->name . '?"',
                    ];
                }
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(['parent','name', 'action','updated_by', 'status'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(KelompokRequest $request)
    {
        $record = new Kelompok;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(Kelompok $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(Kelompok $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(KelompokRequest $request, Kelompok $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Kelompok $record)
    {
        return $record->handleDestroy();
    }

    public function import()
    {
        if (request()->get('download') == 'template') {
            return $this->template();
        }
        return $this->render($this->views . '.import');
    }

    public function template()
    {
        $fileName = date('Y-m-d') . ' Template Import Data ' . $this->prepared('title') . '.xlsx';
        $view = $this->views . '.template';
        $data = [];
        $levels = [
            'boc' => __('Pengawas'),
            'bod' => __('Direktur'),
            'division' => __('Divisi'),
            'department' => __('Departemen'),
            'branch' => __('Cabang'),
            'subbranch' => __('Cabang Pembantu'),
        ];
        $data['levels'] = $levels;
        return \Excel::download(new GenerateExport($view, $data), $fileName);
    }

    public function importSave(Request $request)
    {
        $request->validate(
            [
                'uploads.uploaded' => 'required',
                'uploads.temp_files_ids.*' => 'required',
            ],
            [],
            [
                'uploads.uploaded' => 'File',
                'uploads.temp_files_ids.*' => 'File',
            ]
        );

        $record = new Kelompok;
        return $record->handleImport($request);
    }
}
