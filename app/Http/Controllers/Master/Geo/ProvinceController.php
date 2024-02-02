<?php

namespace App\Http\Controllers\Master\Geo;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Geo\ProvRequest;
use App\Models\Master\Geo\Province;
use Illuminate\Http\Request;
use App\Support\Base;

class ProvinceController extends Controller
{
    protected $module   = 'master_geo_province';
    protected $routes   = 'master.geo.prov';
    protected $views    = 'master.geo.province';
    protected $perms    = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Province',
            'breadcrumb' => [
                'Data Master' => route($this->routes . '.index'),
                'Province' => route($this->routes . '.index'),
            ]
        ]);
    }

    public function index()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:code|label:Kode'),
                        $this->makeColumn('name:name|label:Nama'),
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
        $records = Province::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->editColumn(
                'code',
                function ($record) {
                    return Base::makeLabel($record->code, 'danger');
                }
            )
            ->editColumn(
                'name',
                function ($record) {
                    return Base::makeLabel($record->name, 'primary');
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) use ($user) {
                    $actions = [
                        'type:show|id:' . $record->id,
                        'type:edit|id:' . $record->id,
                    ];
                    if ($record->canDeleted()) {
                        $actions[] = [
                            'type' => 'delete',
                            'id' => $record->id,
                            'attrs' => 'data-confirm-text="' . __('Hapus') . ' ' . $record->name . '?"',
                        ];
                    }
                    return $this->makeButtonDropdown($actions);
                }
            )
            ->rawColumns(['code', 'name', 'action', 'updated_by', 'location'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(ProvRequest $request)
    {
        $record = new Province;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(Province $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(Province $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(ProvRequest $request, Province $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Province $record)
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
        return \Excel::download(new GenerateExport($view, $data), $fileName);
    }

    public function importSave(Request $request)
    {
        $request->validate([
            'uploads.uploaded' => 'required',
            'uploads.temp_files_ids.*' => 'required',
        ], [], [
            'uploads.uploaded' => 'File',
            'uploads.temp_files_ids.*' => 'File',
        ]);

        $record = new Example;
        return $record->handleImport($request);
    }
}
