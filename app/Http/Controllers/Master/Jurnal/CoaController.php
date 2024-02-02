<?php

namespace App\Http\Controllers\Master\Jurnal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Jurnal\CoaRequest;
use App\Models\Globals\Menu;
use App\Models\Master\Jurnal\COA;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CoaController extends Controller
{
    protected $module = 'master.coa';
    protected $routes = 'master.coa';
    protected $views  = 'master.jurnal.coa';
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
                'title' => 'Chart of Accounts (COA)',
                'breadcrumb' => [
                    'Konsol Admin' => route($this->routes . '.index'),
                    'Parameter' => route($this->routes . '.index'),
                    'Jurnal' => route($this->routes . '.index'),
                    'COA' => route($this->routes . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $user = auth()->user();
        $records = COA::grid()->filters()->dtGet();

        return DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'kode_akun',
                function ($record) {
                    return $record->kode_akun;
                }
            )
            ->addColumn(
                'tipe_akun',
                function ($record) {
                    return ucwords($record->tipe_akun);
                }
            )
            ->addColumn(
                'nama_akun',
                function ($record) {
                    return $record->nama_akun;
                }
            )
            ->addColumn(
                'deskripsi',
                function ($record) {
                    return '<p class="my-auto text-left" style="display: -webkit-box; -webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">'.$record->deskripsi . '</p>';
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'status',
                function ($record) {
                    return $record->status;
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
                if ($record->checkAction('delete', $this->perms) && ($record->nama_akun != "Bank" && $record->nama_akun != "Ump")) {
                    $actions[] = [
                        'type' => 'delete',
                        'id' => $record->id,
                        'attrs' => 'data-confirm-text="' . __('Hapus Parameter Chart of Accounts (COA) ') .$record->kode_akun . '?"',
                    ];
                }
                // if ($record->checkAction('show', $this->perms)) {
                //     $actions[] = 'type:show|id:' . $record->id;
                // }
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(
                [
                    'kode_akun',
                    'nama_akun',
                    'tipe_akun',
                    'deskripsi',
                    'status',
                    'updated_by',
                    'action'
                ]
            )
            ->make(true);
    }

    public function index()
    {
        $data["baseContentReplace"] = true;
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:kode_akun|label:Kode Akun|className:text-left'),
                    $this->makeColumn('name:nama_akun|label:Nama Akun|className:text-left'),
                    $this->makeColumn('name:tipe_akun|label:Tipe Akun Utama|className:text-center'),
                    $this->makeColumn('name:deskripsi|label:Deskripsi|width:200px|classname:text-left'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);

        return $this->render($this->views . '.index', $data);
    }

    public function create(){
        $page_action = "create";
        $baseContentReplace = "base-modal--render";
        return $this->render($this->views . '.create');
    }

    public function show(COA $record){
        $this->prepare(
            [
                'breadcrumb' => [
                    'Konsol Admin' => route($this->routes . '.index'),
                    'Parameter' => route($this->routes . '.index'),
                    'Jurnal' => route($this->routes . '.index'),
                    'COA' => route($this->routes . '.index'),
                    'Lihat' => route($this->routes . '.show', $record->id),
                ]
            ]
        );
        $page_action = "show";
        return $this->render($this->views . '.detail', compact("page_action", "record"));
    }
    public function store(CoaRequest $request){
        $record = new Coa;
        return $record->handleStoreOrUpdate($request);
    }

    public function edit(COA $record){
        $this->prepare(
            [
                'breadcrumb' => [
                    'Konsol Admin' => route($this->routes . '.index'),
                    'Parameter' => route($this->routes . '.index'),
                    'Jurnal' => route($this->routes . '.index'),
                    'COA' => route($this->routes . '.index'),
                    'Detil' => route($this->routes . '.edit', $record->id),
                ]
            ]
        );
        $page_action = "edit";
        return $this->render($this->views . '.detail', compact("page_action", "record"));
    }

    public function update(COA $record, CoaRequest $request){
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(COA $record){
        return $record->handleDestroy();
    }

    public function getDetailCOA(Request $request){
        $id_akun = $request->id_akun;
        $coa = COA::where('id', $id_akun)->first();
        return response()->json([
            'kode_akun' => $coa->kode_akun,
            'tipe_akun' => $coa->tipe_akun,
        ]);
    }
}
