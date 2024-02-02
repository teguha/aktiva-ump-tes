<?php

namespace App\Http\Controllers\Master\Org;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Org\PositionRequest;
use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Org\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    protected $module = 'master_org_position';
    protected $routes = 'master.org.position';
    protected $views = 'master.org.position';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Jabatan',
            'breadcrumb' => [
                'Konsol Admin' => route($this->routes . '.index'),
                'Parameter' => route($this->routes . '.index'),
                'Stuktur Organisasi' => route($this->routes . '.index'),
                'Jabatan' => route($this->routes . '.index'),
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
                    $this->makeColumn('name:location|label:Lokasi|className:text-left'),
                    $this->makeColumn('name:kelompok|label:Kelompok Jabatan|className:text-left'),
                    // $this->makeColumn('name:parent|label:Parent|className:text-left'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);
        return $this->render($this->views . '.index');
    }

    public function grid()
    {
        $user = auth()->user();
        $records = Position::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('location', function ($record) {
                return $record->location->name ?? '';
            })
            ->addColumn('kelompok', function ($record) {
                return $record->kelompok->name ?? '';
            })
            ->addColumn('parent', function ($record) {
                if ($record->code == 1001) {
                    return OrgStruct::where("level", "root")->first()->name;
                }
                return $record->parent->name ?? '';
            })
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
                        'attrs' => 'data-confirm-text="' . __('Hapus parameter Jabatan') . ' ' . $record->name . '?"',
                    ];
                }
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(['location', 'kelompok', 'action', 'updated_by', 'location', 'status'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(PositionRequest $request)
    {
        $record = new Position;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(Position $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(Position $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(PositionRequest $request, Position $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Position $record)
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
        $request->validate([
            'uploads.uploaded' => 'required',
            'uploads.temp_files_ids.*' => 'required',
        ], [], [
            'uploads.uploaded' => 'File',
            'uploads.temp_files_ids.*' => 'File',
        ]);

        $record = new Position;
        return $record->handleImport($request);
    }
}
