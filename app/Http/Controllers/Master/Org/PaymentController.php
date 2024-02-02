<?php

namespace App\Http\Controllers\Master\Org;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Org\PaymentRequest;
use App\Models\Master\Org\OrgStruct;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $module = 'master_org_payment';
    protected $routes = 'master.org.payment';
    protected $views = 'master.org.payment';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'title' => 'Payment Point',
            'breadcrumb' => [
                'Data Master' => route($this->routes . '.index'),
                'Stuktur Organisasi' => route($this->routes . '.index'),
                'Payment Point' => route($this->routes.'.index'),
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
                    $this->makeColumn('name:parent|label:Parent|className:text-center'),
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
        $records = OrgStruct::payment()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('parent', function ($record) {
                return $record->parent->name ?? null;
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
                        'attrs' => 'data-confirm-text="'.__('Hapus').' '.$record->name.'?"',
                    ];
                }
                return $this->makeButtonDropdown($actions);
            })
            ->rawColumns(['action','updated_by'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views.'.create');
    }

    public function store(PaymentRequest $request)
    {
        $record = new OrgStruct;
        return $record->handleStoreOrUpdate($request, 'payment');
    }

    public function show(OrgStruct $record)
    {
        return $this->render($this->views.'.show', compact('record'));
    }

    public function edit(OrgStruct $record)
    {
        return $this->render($this->views.'.edit', compact('record'));
    }

    public function update(PaymentRequest $request, OrgStruct $record)
    {
        return $record->handleStoreOrUpdate($request, 'payment');
    }

    public function destroy(OrgStruct $record)
    {
        return $record->handleDestroy();
    }
}
