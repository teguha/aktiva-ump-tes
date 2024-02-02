<?php

namespace App\Http\Controllers\Setting\Flow;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Flow\FlowRequest;
use App\Models\Globals\Menu;
use Illuminate\Http\Request;

class FlowController extends Controller
{
    protected $module = 'setting_flow';
    protected $routes = 'setting.flow';
    protected $views = 'setting.flow';
    protected $perms = 'setting';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms.'.view',
            'title' => 'Flow Approval',
            'breadcrumb' => [
                'Pengaturan Umum' => route($this->routes.'.index'),
                'Flow Approval' => route($this->routes.'.index'),
            ]
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:menu|label:Menu|className:text-left|sortable:false'),
                    $this->makeColumn('name:flows|label:Flow Approval|className:text-center|sortable:false'),
                    $this->makeColumn('name:updated_by|label:Diperbarui|width:180px|sortable:false'),
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);
        return $this->render($this->views.'.index');
    }

    public function grid()
    {
        $records = Menu::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('menu', function ($record) {
                return ($record->parent ? "<span class=pl-4>&#8627; </span>" . "<span>$record->show_module</span>" : "<span class=text-bold>$record->show_module</span>");
            })
            ->addColumn('flows', function ($record) {
                if($record->flows->count() == 0 ){
                    return "";
                }

                $html = '<div class="d-flex align-items-center justify-content-center">';
                $colors = [
                    1 => 'primary',
                    2 => 'info',
                ];
                $orders = $record->flows()->get()->groupBy('order');
                foreach ($orders as $i => $flows) {
                    foreach ($flows as $j => $flow) {
                        $html .= '<span class="label label-light-'.$colors[$flow->type].' font-weight-bold label-inline text-nowrap" data-toggle="tooltip" title="'.$flow->show_type.'">'.$flow->role->name.'</span>';

                        if(!($i === $orders->keys()->last() && $j === $flows->keys()->last())){
                            $html .= '<i class="mx-2 fas fa-angle-double-right text-muted"></i>';
                        }
                    }
                }
                $html .= "</div>";

                return $html;
            })
            ->editColumn('updated_by', function ($record) {
                return $record->createdByRaw();
            })
            ->addColumn('action', function ($record) {
                $actions = [];
                if (!$record->child()->exists()) {
                    $actions[] = [
                        'type' => 'edit',
                        'page' => true,
                        'icon' => 'far fa-check-circle text-success',
                        'label' => 'Assign Approval',
                        'id' => $record->id,
                    ];
                    $actions[] = [
                        'type' => 'history',
                        'attrs' => 'data-modal-size="modal-md"',
                        'id' => $record->id,
                    ];
                }
                return $this->makeButtonDropdown($actions);
            })
            ->rawColumns(['action','updated_by','menu','flows'])
            ->make(true);
    }

    public function edit(Menu $record)
    {
        return $this->render($this->views.'.edit', compact('record'));
    }

    public function update(FlowRequest $request, Menu $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function history(Menu $record)
    {
        $this->prepare(['title' => 'History Aktivitas']);
        return $this->render('globals.history', compact('record'));
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
        $records = Menu::grid()->whereNotNull('parent_id')->get();
        $colors = ['#3699FF','#1BC5BD','#8950FC','#28C76F','#FFA800'];

        $view = $this->views.'.template';
        $data['records'] = $records;
        $data['colors'] = $colors;

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

        $record = new Menu;
        return $record->handleImport($request);
    }
}
