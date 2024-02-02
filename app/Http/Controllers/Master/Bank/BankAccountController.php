<?php

namespace App\Http\Controllers\Master\Bank;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Bank\BankAccountRequest;
use App\Models\Master\Bank\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    protected $module = 'master_bank-account';
    protected $routes = 'master.bank-account';
    protected $views = 'master.bank-account';
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
                    $this->makeColumn('name:user_id|label:Pemilik|className:text-center'),
                    $this->makeColumn('name:number|label:No Rekening|className:text-center'),
                    $this->makeColumn('name:bank|label:Bank|className:text-center'),
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
        $records = BankAccount::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('number', function ($record) {
                return "<span class='badge badge-danger' style='background-color:#0000ff; color:white;'>" . $record->number  . "</span>";
            })
            ->addColumn('user_id', function ($record) {
                return "<span class='badge badge-danger'>" . $record->owner->name  . "</span>";
            })
            ->addColumn('bank', function ($record) {
                return "<span class='badge badge-success'>" . $record->show_bank  . "</span>";
            })
            ->addColumn('updated_by', function ($record) {
                return $record->createdByRaw();
            })
            ->addColumn('action', function ($record) use ($user) {
                $actions = [
                    'type:show',
                    'type:edit',
                ];
                if ($record->canDeleted()) {
                    $actions[] = [
                        'type' => 'delete',
                        'text' => $record->name,
                    ];
                }
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(['action','updated_by','number','user_id','bank'])
            ->make(true);
    }

    public function create()
    {
        $record = new BankAccount;
        return $this->render($this->views.'.create', compact('record'));
    }

    public function store(BankAccountRequest $request)
    {
        $record = new BankAccount;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(BankAccount $record)
    {
        return $this->render($this->views.'.show', compact('record'));
    }

    public function edit(BankAccount $record)
    {
        return $this->render($this->views.'.edit', compact('record'));
    }

    public function update(BankAccountRequest $request, BankAccount $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(BankAccount $record)
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
        
        $record = new BankAccount;
        return $record->handleImport($request);
    }
}
