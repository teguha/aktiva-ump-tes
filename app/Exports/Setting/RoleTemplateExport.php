<?php

namespace App\Exports\Setting;

use App\Models\Auth\Role;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RoleTemplateExport implements FromView
{
    public function view(): View
    {
        $modules = \Base::getModulesPerms();
        $actions = ['view','create','edit','delete','approve'];
        $colors = ['#3699FF','#1BC5BD','#8950FC','#28C76F','#FFA800'];

        return view('exports.setting.role.template', compact('modules','actions','colors'));
    }
}
