<?php

namespace App\Http\Controllers\Master\Geo;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Geo\CityRequest;
use App\Http\Requests\Master\Geo\DistrictRequest;
use App\Models\Master\Geo\City;
use App\Models\Master\Geo\District;
use App\Models\Master\Geo\Province;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    protected $module   = 'master_geo_district';
    protected $routes   = 'master.geo.district';
    protected $views    = 'master.geo.district';
    protected $perms    = 'master';

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'Kecamatan',
                'breadcrumb' => [
                    'Data Master' => route($this->routes . '.index'),
                    'Kecamatan' => route($this->routes . '.index'),
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
                        $this->makeColumn('name:code|label:Kode|className:text-left'),
                        $this->makeColumn('name:name|label:Nama|className:text-left'),
                        $this->makeColumn('name:city.name|label:Kota|className:text-left'),
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
        $records = District::with('city')
            ->grid()
            ->filters()
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
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
            ->rawColumns(['action', 'updated_by', 'location'])
            ->make(true);
    }

    public function create()
    {
        $CITIES = City::orderBy('name', 'ASC')->get();
        return $this->render(
            $this->views . '.create',
            compact('CITIES')
        );
    }

    public function store(DistrictRequest $request)
    {
        $record = new District;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(District $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(District $record)
    {
        $CITIES = City::orderBy('name', 'ASC')->get();
        return $this->render(
            $this->views . '.edit',
            compact('record', 'CITIES')
        );
    }

    public function update(DistrictRequest $request, District $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(District $record)
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
