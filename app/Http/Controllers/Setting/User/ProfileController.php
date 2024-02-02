<?php

namespace App\Http\Controllers\Setting\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\User\UserRequest;
use App\Models\Auth\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $module = 'setting_profile';
    protected $routes = 'setting.profile';
    protected $views = 'setting.profile';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'title' => 'Profile',
            'breadcrumb' => [
                'Profile' => route($this->routes.'.index'),
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
                    $this->makeColumn('name:email|label:Email|className:text-center'),
                    $this->makeColumn('name:nik|label:NIK|className:text-center'),
                    $this->makeColumn('name:position|label:Jabatan|className:text-center'),
                    $this->makeColumn('name:role|label:Role|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);
        return $this->render($this->views.'.index');
    }

    public function updateProfile(Request $request)
    {
        return auth()->user()->handleUpdateProfile($request);
    }

    public function notification()
    {
        auth()->user()->notifications()
                    ->wherePivot('readed_at', null)
                    ->newPivotStatement()
                    ->where('user_id', auth()->id())
                    ->update(array('readed_at' => now()));

        $this->prepare([
            'title' => 'Notifikasi',
            'breadcrumb' => [
                'Notifikasi'    => route($this->routes.'.notification'),
            ],
            'tableStruct' => [
                'url' => route($this->routes.'.gridNotification'),
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:module|label:Modul|className:text-left'),
                    $this->makeColumn('name:message|label:Deskripsi|className:text-left'),
                    $this->makeColumn('name:created_by'),
                    // $this->makeColumn('name:action'),
                ],
            ],
        ]);

        return $this->render($this->views.'.notification');
    }

    public function gridNotification()
    {
        $records = auth()->user()->notifications()->latest()->dtGet();
        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('module', function ($record) {
                return $record->show_module;
            })
            ->addColumn('message', function ($record) {
                return $record->show_message;
            })
            ->addColumn('created_by', function ($record) {
                return $record->createdByRaw();
            })
            ->addColumn('action', function ($record) {
                $buttons = '';
                $buttons .= $this->makeButton([
                    'type'  => 'url',
                    'id'    => $record->id,
                    'url'   => $record->link
                ]);
                return $buttons;
            })
            ->rawColumns(['action', 'created_by'])
            ->make(true);
    }

    public function activity()
    {
        $this->prepare([
            'title' => 'Aktivitas',
            'breadcrumb' => [
                'Aktivitas'    => route($this->routes.'.activity'),
            ],
            'tableStruct' => [
                'url' => route($this->routes.'.gridActivity'),
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:module|label:Modul|className:text-left'),
                    $this->makeColumn('name:message|label:Deskripsi|className:text-left'),
                    $this->makeColumn('name:created_by'),
                    // $this->makeColumn('name:action'),
                ],
            ],
        ]);

        return $this->render($this->views.'.activity');
    }

    public function gridActivity()
    {
        $records = auth()->user()->activities()->grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('module', function ($record) {
                return $record->show_module;
            })
            ->addColumn('message', function ($record) {
                return $record->show_message;
            })
            ->editColumn('created_by', function ($record) {
                return $record->createdByRaw();
            })
            ->rawColumns(['action','created_by'])
            ->make(true);
    }

    public function changePassword()
    {
        $this->prepare([
            'title' => 'Ubah Password',
            'breadcrumb' => [
                'Ubah Password'    => route($this->routes.'.changePassword'),
            ],
        ]);
        return $this->render($this->views.'.changePassword');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password'          => 'required|password',
            'new_password'              => 'required|confirmed',
            'new_password_confirmation' => 'required',
        ]);

        return auth()->user()->handleUpdatePassword($request);
    }
}
