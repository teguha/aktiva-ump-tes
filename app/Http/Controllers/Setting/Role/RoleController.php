<?php

namespace App\Http\Controllers\Setting\Role;

use App\Exports\Setting\RoleTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Role\RoleRequest;
use App\Imports\Setting\RoleImport;
use App\Models\Auth\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $module = 'setting_role';
    protected $routes = 'setting.role';
    protected $views = 'setting.role';
    protected $perms = 'setting';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms.'.view',
            'title' => 'Hak Akses',
            'breadcrumb' => [
                'Pengaturan Umum' => route($this->routes.'.index'),
                'Hak Akses' => route($this->routes.'.index'),
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
                    $this->makeColumn('name:users|label:Jumlah User|className:text-center|width:200px'),
                    $this->makeColumn('name:permissions|label:Jumlah Akses|className:text-center|width:200px'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);
        return $this->render($this->views.'.index');
    }

    public function grid()
    {
        $records = Role::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('users', function ($record) {
                return $record->users()->count() . ' User';
            })
            ->addColumn('permissions', function ($record) {
                return $record->permissions()->count() . ' Permission';
            })
            ->editColumn('updated_by', function ($record) {
                return $record->createdByRaw();
            })
            ->addColumn('action', function ($record) {
                $actions = [];
                $actions[] = [
                    'type' => 'edit',
                    'id' => $record->id,
                ];
                $actions[] = [
                    'page' => true,
                    'icon' => 'fa fa-check text-primary',
                    'label' => 'Assign Permission',
                    'url' => route($this->routes.'.permit', $record->id)
                ];

                if ($record->canDeleted()) {
                    $actions[] = [
                        'type' => 'delete',
                        'id' => $record->id,
                        'attrs' => 'data-confirm-text="'.__('Hapus').' Role '.$record->name.'?"',
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

    public function store(RoleRequest $request)
    {
        $record = new Role;
        return $record->handleStoreOrUpdate($request);
    }

    public function edit(Role $record)
    {
        return $this->render($this->views.'.edit', compact('record'));
    }

    public function update(RoleRequest $request, Role $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Role $record)
    {
        return $record->handleDestroy();
    }

    public function permit(Role $record)
    {
        return $this->render($this->views.'.permit', compact('record'));
    }

    public function grant(Role $record, Request $request)
    {
        return $record->handleGrant($request);
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
        return \Excel::download(new RoleTemplateExport, $fileName);
    }

    public function importSave(Request $request)
    {
        $request->validate([
            'uploads.uploaded' => 'required'
        ],[],[
            'uploads.uploaded' => 'File'
        ]);
        
        $record = new Role;
        return $record->handleImport($request);
    }
}
