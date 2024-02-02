<?php

namespace App\Http\Controllers\Master\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Bank\BankRequest;
use App\Models\Globals\Menu;
use App\Models\Master\Bank\Bank;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BankController extends Controller
{
    protected $module = 'master.bank';
    protected $routes = 'master.bank';
    protected $views  = 'master.bank';
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
                'title' => 'Bank',
                'breadcrumb' => [
                    'Data Master' => route($this->routes . '.index'),
                    'Bank' => route($this->routes . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $user = auth()->user();
        $records = Bank::grid()->filters()->dtGet();

        return DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'bank',
                function ($record) {
                    return $record->bank;
                }
            )
            ->addColumn(
                'alamat',
                function ($record) {
                    return $record->alamat;
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
                        'attrs' => 'data-confirm-text="' . __('Hapus Parameter Bank') . ' ' . $record->bank . '?"',
                    ];
                }
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(
                [
                    'bank',
                    'alamat',
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
                    $this->makeColumn('name:bank|label:Bank|className:text-left'),
                    $this->makeColumn('name:alamat|label:Alamat|className:text-left'),
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

    public function show(Bank $record){
        $baseContentReplace = false;
        $page_action = "show";
        return $this->render($this->views . '.detail', compact("page_action", "record", "baseContentReplace"));
    }
    public function store(BankRequest $request){
        $record = new Bank;
        return $record->handleStoreOrUpdate($request);
    }

    public function edit(Bank $record){
        $page_action = "edit";
        return $this->render($this->views . '.detail', compact("page_action", "record"));
    }

    public function update(Bank $record, BankRequest $request){
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Bank $record){
        return $record->handleDestroy();
    }
}
