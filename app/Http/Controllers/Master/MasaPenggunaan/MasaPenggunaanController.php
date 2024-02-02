<?php

namespace App\Http\Controllers\Master\MasaPenggunaan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\MasaPenggunaan\MasaPenggunaanRequest;
use App\Models\Globals\Menu;
use App\Models\Master\MasaPenggunaan\MasaPenggunaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasaPenggunaanController extends Controller
{
    protected $module = 'master.masa-penggunaan';
    protected $routes = 'master.masa-penggunaan';
    protected $views  = 'master.masa-penggunaan';
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
                'title' => 'Masa Penggunaan Aset Tangible',
                'breadcrumb' => [
                    'Konsol Admin' => route($this->routes . '.index'),
                    'Parameter' => route($this->routes . '.index'),
                    'Masa Penggunaan Aset Tangible' => route($this->routes . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $user = auth()->user();
        $records = MasaPenggunaan::grid()->filters()->dtGet();

        return DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'masa_penggunaan',
                function ($record) {
                    return $record->masa_penggunaan;
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
                    $actions[] = 'type:edit|id:' . $record->id . 'label:Detil';
                }
                if ($record->checkAction('delete', $this->perms)) {
                    $actions[] = [
                        'type' => 'delete',
                        'id' => $record->id,
                        'attrs' => 'data-confirm-text="' . __('Hapus Parameter Masa Penggunaan Asset Tangible') . ' ' . $record->masa_penggunaan . '?"',
                    ];
                }
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(
                [
                    'masa_penggunaan',
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
                    $this->makeColumn('name:masa_penggunaan|label:Masa Penggunaan Aset Tangible (Tahun)|className:text-center'),
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

    public function show(MasaPenggunaan $record){
        $page_action = "show";
        return $this->render($this->views . '.detail', compact("page_action", "record"));
    }
    public function store(MasaPenggunaanRequest $request){
        $record = new MasaPenggunaan;
        return $record->handleStoreOrUpdate($request);
    }

    public function edit(MasaPenggunaan $record){
        $page_action = "edit";
        return $this->render($this->views . '.detail', compact("page_action", "record"));
    }

    public function update(MasaPenggunaan $record, MasaPenggunaanRequest $request){
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(MasaPenggunaan $record){
        return $record->handleDestroy();
    }
}
