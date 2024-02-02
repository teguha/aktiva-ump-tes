<?php

namespace App\Http\Controllers\Master\Org;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Org\RootRequest;
use App\Models\Master\Org\OrgStruct;
use Illuminate\Http\Request;

class RootController extends Controller
{
    protected $module = 'master_org_root';
    protected $routes = 'master.org.root';
    protected $views = 'master.org.root';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms.'.view',
            'title' => 'Root',
            'breadcrumb' => [
                'Data Master' => route($this->routes . '.index'),
                'Stuktur Organisasi' => route($this->routes . '.index'),
                'Root' => route($this->routes.'.index'),
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
                    $this->makeColumn('name:phone|label:Telepon|className:text-center'),
                    $this->makeColumn('name:address|label:Alamat|className:text-left|width:400px'),
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
        $records = OrgStruct::root()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('name', function ($record) {
                return "<span class='badge badge-danger'>" . $record->name . "</span>";
            })
            ->addColumn('phone', function ($record) {
                return "<span class='badge badge-danger' style='background-color:#0000ff; color:white;'>" . $record->phone . "</span>";
            })
            ->addColumn('address', function ($record) {
                return "<span class='badge badge-success'>" . $record->address . "</span>";
            })
            ->addColumn('updated_by', function ($record) {
                return $record->createdByRaw();
            })
            ->addColumn('action', function ($record) use ($user) {
                $actions = [
                    'type:show|id:'.$record->id,
                    'type:edit|id:'.$record->id,
                ];
                return $this->makeButtonDropdown($actions);
            })
            ->rawColumns(['action','updated_by', 'name', 'phone', 'address'])
            ->make(true);
    }

    public function show(OrgStruct $record)
    {
        // $record = OrgStruct::with('city')->where('id', $record->id)->first()->get();
        return $this->render($this->views.'.show', compact('record'));
    }

    public function edit(OrgStruct $record)
    {
        // $record = OrgStruct::with('city')->where('id', $record->id)->first()->get();
        return $this->render($this->views.'.edit', compact('record'));
    }

    public function update(RootRequest $request, OrgStruct $record)
    {
        return $record->handleStoreOrUpdate($request, 'root');
    }
}
