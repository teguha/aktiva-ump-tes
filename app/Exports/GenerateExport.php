<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class GenerateExport implements FromView
{
    protected $viewPath;
    protected $data;

    public function __construct($viewPath, $data = [])
    {
        $this->viewPath = $viewPath;
        $this->data = $data;
    }

    public function view(): View
    {
        return view($this->viewPath, $this->data);
    }
}
