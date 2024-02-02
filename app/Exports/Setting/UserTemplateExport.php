<?php

namespace App\Exports\Setting;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UserTemplateExport implements FromView
{
    public function view(): View
    {
        return view('exports.setting.user.template');
    }
}
