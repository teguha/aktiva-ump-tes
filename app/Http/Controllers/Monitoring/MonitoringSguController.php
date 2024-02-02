<?php

namespace App\Http\Controllers\Monitoring;

use App\Models\Globals\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Sgu\PengajuanSgu;


class MonitoringSguController extends Controller
{
    protected $module = 'monitoringsgu';
    protected $routes = 'monitoring.monitoring-sgu';
    protected $views  = 'monitoring.monitoring-sgu';
    protected $perms = 'monitoring';

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'Monitoring SGU',
                'breadcrumb' => [
                    'Monitoring' => route($this->routes . '.index'),
                    'Monitoring SGU' => route($this->routes . '.index'),
                ]
            ]
        );
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:work_unit|label:Unit Kerja|className:text-center'),
                    $this->makeColumn('name:rent_location|label:Lokasi Sewa|className:text-center'),
                    $this->makeColumn('name:rent_time_cost|label:Periode Sewa|className:text-center'),
                    $this->makeColumn('name:cost|label:Nilai Sewa|className:text-center'),
                    $this->makeColumn('name:deposit|label:Deposito|className:text-center'),
                    $this->makeColumn('name:status|label:Status Terakhir|className:text-center'),
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);

        return $this->render($this->views . '.index');
    }

    public function grid()
    {
        $user = auth()->user();
        $records = PengajuanSgu::grid()->filters()->dtGet();

        return DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'work_unit',
                function ($record) {
                    return $record->workUnit->name ?? '-';
                }
            )
            ->addColumn(
                'rent_time_cost',
                function ($record) {
                    return $record->rent_cost ? $record->rent_time_period . " Bulan" : '-';
                }
            )
            ->addColumn(
                'cost',
                function ($record) {
                    return $record->rent_cost ? number_format($record->rent_cost, 0, ',', '.') : '-';
                }
            )
            ->addColumn(
                'cost',
                function ($record) {
                    return $record->deposit ? number_format($record->deposit, 0, ',', '.') : '-';
                }
            )
            ->addColumn('status', function ($record) {
                return $record->status;
            })
            
            ->addColumn('action', function ($record) use ($user) {
                $checkId = $record->id;
                $checkIdAset = $record->aset->id ?? null;
                $status = $checkIdAset;
                $actions = [];
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(
                [
                    'cost',
                    'rent_time_cost',
                    'deposito',
                    'status',
                    'action',
                ]
            )
            ->make(true);
    }
}
