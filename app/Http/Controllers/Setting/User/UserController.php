<?php

namespace App\Http\Controllers\Setting\User;

use App\Exports\Setting\UserTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\User\UserRequest;
use App\Models\Auth\User;
use App\Models\Master\RekeningBank\RekeningBank;
use App\Support\Base;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $module = 'setting_user';
    protected $routes = 'setting.user';
    protected $views = 'setting.user';
    protected $perms = 'setting';

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'Manajemen User',
                'breadcrumb' => [
                    'Pengaturan Umum' => route($this->routes . '.index'),
                    'Manajemen User' => route($this->routes . '.index'),
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
                        // $this->makeColumn('name:nik|label:NIK|className:text-left'),
                        $this->makeColumn('name:name|label:Nama Lengkap|className:text-center'),
                        $this->makeColumn('name:username|label:Username|className:text-center'),
                        $this->makeColumn('name:lokasi|label:Struktur|className:text-center'),
                        $this->makeColumn('name:position|label:Jabatan|className:text-center'),
                        $this->makeColumn('name:role|label:Hak Akses|className:text-center'),
                        // $this->makeColumn('name:email|label:Email|className:text-left'),
                        // $this->makeColumn('name:bank|label:Bank, No Rekening, Nama Pemilik|className:text-center'),
                        $this->makeColumn('name:status'),
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
        $records = User::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'nik',
                function ($record) {
                    return $record->nik;
                }
            )
            ->addColumn(
                'name',
                function ($record) {
                    return $record->name;
                    // return Base::makeLabel($record->name, 'danger');
                }
            )
            ->addColumn(
                'username',
                function ($record) {
                    return $record->username;
                    // return Base::makeLabel($record->username, 'info');
                }
            )
            ->addColumn(
                'lokasi',
                function ($record) {
                    return $record->position->location->name ?? '-';
                }
            )
            ->addColumn(
                'position',
                function ($record) {
                    return $record->position->name ?? '-';
                }
            )
            ->addColumn(
                'role',
                function ($record) {
                    if ($record->roles()->exists()) {
                        return $record->roles()->pluck('name')->toArray();
                        // return Base::makeLabel(implode('<br>', $record->roles()->pluck('name')->toArray()), 'danger');
                    }
                    return '-';
                }
            )
            ->addColumn(
                'bank',
                function ($record) {
                    $label = '';
                    $rekening = RekeningBank::where('user_id', $record->id)->get();
                    if ($rekening) {
                        foreach ($rekening as $r) {
                            if ($label) $label .= "<br>";
                            $label .= $r->bank->name . ' <br>' . $r->no_rekening . '<br>' . $r->pemilik->name;
                        }
                    }

                    return $label;
                }
            )
            ->editColumn(
                'status',
                function ($record) {
                    return $record->status;
                }
            )
            ->editColumn(
                'updated_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) use ($user) {
                    $actions = [];
                    $actions[] = [
                        'type' => 'show',
                        'id' => $record->id,
                        'page' => false
                    ];

                    $actions[] = [
                        'type' => 'edit',
                        'id' => $record->id,
                    ];

                    if ($record->canDeleted()) {
                        $actions[] = [
                            'type' => 'delete',
                            'id' => $record->id,
                            'attrs' => 'data-confirm-text="' . __('Hapus') . ' username ' . $record->name . '?"',
                        ];
                    }

                    if ($user->id == 1 && $record->status != 'nonactive') {
                        $actions[] = [
                            'label' => 'Reset Password',
                            'icon' => 'fa fa-retweet text-warning',
                            'class' => 'base-form--postByUrl',
                            'attrs' => 'data-swal-text="Reset password akan mengubah password menjadi: qwerty123456"',
                            'id' => $record->id,
                            'url' => route($this->routes . '.resetPassword', $record->id)
                        ];
                    }
                    return $this->makeButtonDropdown($actions);
                }
            )
            ->rawColumns(
                [
                    'name',
                    'username',
                    'bank',
                    'lokasi',
                    'role',
                    'position',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function addBank(Request $request)
    {
        $latest_id = $request->latest_id;
        $id = $latest_id + 1;
        return $this->render($this->views . '.subs.new_bank', compact('id'));
    }

    public function store(UserRequest $request)
    {
        $record = new User;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(User $record)
    {
        $action = 'show';
        $this->pushBreadcrumb(['Lihat' => route($this->routes . '.show', $record)]);
        return $this->render($this->views . '.show', compact('record', 'action'));
    }

    public function edit(User $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(UserRequest $request, User $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(User $record)
    {
        return $record->handleDestroy();
    }

    public function resetPassword(User $record)
    {
        return $record->handleResetPassword();
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
        return \Excel::download(new UserTemplateExport, $fileName);
    }

    public function importSave(Request $request)
    {
        $request->validate(
            [
                'uploads.uploaded' => 'required'
            ],
            [],
            [
                'uploads.uploaded' => 'File'
            ]
        );

        $record = new User;
        return $record->handleImport($request);
    }
}
