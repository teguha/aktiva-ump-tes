<?php

namespace App\Http\Controllers\Master\MataAnggaran;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\MataAnggaran\MataAnggaranRequest;
use App\Models\Globals\Menu;
use App\Models\Master\MataAnggaran\MataAnggaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MataAnggaranController extends Controller
{
    protected $module = 'master.mata-anggaran';
    protected $routes = 'master.mata-anggaran';
    protected $views  = 'master.mata-anggaran';
    protected $perms = 'master';
    private $datas;

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'Mata Anggaran',
                'breadcrumb' => [
                    'Konsol Admin' => route($this->routes . '.index'),
                    'Parameter' => route($this->routes . '.index'),
                    'Mata Anggaran' => route($this->routes . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $user = auth()->user();
        $records = MataAnggaran::grid()->filters()->dtGet();

        return DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'mata_anggaran',
                function ($record) {
                    return $record->mata_anggaran;
                }
            )
            ->addColumn(
                'nama',
                function ($record) {
                    return $record->nama;
                }
            )
            ->addColumn(
            'deskripsi',
            function ($record) {
                return '<div style="margin-right: 0; width:250px"><p class="text-left" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">' . ($record->deskripsi ? $record->deskripsi : "-") . '</p></div>';
            }

            )
            ->addColumn(
                'status',
                function ($record) {
                    return $record->status;
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
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
                        'attrs' => 'data-confirm-text="' . __('Hapus Parameter Mata Anggaran') . ' ' . $record->mata_anggaran . '?"',
                    ];
                }
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(
                [
                    'mata_anggaran',
                    'nama',
                    'deskripsi',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:mata_anggaran|label:Mata Anggaran|className:text-left'),
                    $this->makeColumn('name:nama|label:Nama Mata Anggaran|className:text-left'),
                    $this->makeColumn('name:deskripsi|label:Deskripsi|className:text-left'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);

        return $this->render($this->views . '.index');
    }

    public function create(){
        $page_action = "create";
        return $this->render($this->views . '.detail', compact("page_action"));
    }

    public function show(MataAnggaran $record){
        $baseContentReplace = false;
        $page_action = "show";
        return $this->render($this->views . '.detail', compact("page_action", "record", "baseContentReplace"));
    }
    public function store(MataAnggaranRequest $request){
        $record = new MataAnggaran;
        return $record->handleStoreOrUpdate($request);
    }

    public function edit(MataAnggaran $record){
        $page_action = "edit";
        return $this->render($this->views . '.detail', compact("page_action", "record"));
    }

    public function update(MataAnggaran $record, MataAnggaranRequest $request){
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(MataAnggaran $record){
        return $record->handleDestroy();
    }

    public function getDetailMataAnggaran(Request $request){
        $id_mata_anggaran = $request->id_mata_anggaran;
        $mata_anggaran = MataAnggaran::where('id', $id_mata_anggaran)->first()->mata_anggaran;
        return response()->json([
            'mata_anggaran' => $mata_anggaran,
        ]);
    }
}
