<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\PelepasanAktiva\PenghapusanAktiva;
use App\Models\PelepasanAktiva\PenjualanAktiva;
use App\Support\Base;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;



class LaporanPenghapusanController extends Controller
{
    protected $module = 'laporan.laporan-penghapusan';
    protected $routes = 'laporan.laporan-penghapusan';
    protected $views  = 'laporan.laporan-penghapusan';
    protected $perms = 'laporan';

    const TYPE = [
        'pengajuan'           => [
            'route' => 'pelepasan-aktiva.penghapusan',
            'scope' => 'gridStatusCompleted',
            'show'  => 'Pengajuan',
        ],
        'realization'           => [
            'route' => 'pelepasan-aktiva.pelaksanaan-penghapusan',
            'scope' => 'gridRealizationStatusCompleted',
            'show'  => 'Pelaksanaan',
        ],
    ];

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'Laporan Penghapusan',
                'breadcrumb' => [
                    'Laporan' => route($this->routes . '.index'),
                    'Laporan Penghapusan' => route($this->routes . '.index'),
                ],
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
                        $this->makeColumn('name:pengajuan|label:Penghapusan Aktiva|className:text-center'),
                        $this->makeColumn('name:struct|label:Unit Kerja|className:text-center'),
                        $this->makeColumn('name:asset|label:Jumlah Aktiva|className:text-center'),
                        $this->makeColumn('name:status|label:Status|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action_show|label:Aksi'),
                    ],
                ]
            ]
        );

        return $this->render(
            $this->views . '.index',
            [
                'TYPE'  => Self::TYPE,
            ]
        );
    }

    public function grid(Request $request)
    {
        $user = auth()->user();
        $records = [];
        $type       = Self::TYPE[$request->type] ?? [];
        $route      = $type['route'] ?? '';
        $scope      = $type['scope'] ?? '';
        $show       = $type['show'] ?? '';
        if (in_array($request->type, ['pengajuan', 'realization']) || ($request->date_start || $request->date_end || $request->struct_id)) {
            $this->prepare(
                [
                    'routes' => $route,
                ]
            );
            $records = PenghapusanAktiva::$scope()->filters()->dtGet();
        }

        return DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'pengajuan',
                function ($record) {
                    return $record->code . "<br>" . $record->date->format('d/m/Y');
                }
            )
            ->addColumn(
                'struct',
                function ($record) {
                    return $record->struct->name;
                }
            )
            ->addColumn(
                'asset',
                function ($record) {
                    return $record->details->count() ??  "-";
                }
            )
            ->addColumn(
                'status',
                function ($record) {
                    return $record->labelStatus($record->status ?? 'new');
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn('action_show', function ($record) use ($user, $request, $route) {
                $actions = [];
                $actions[] = [
                    'type' => 'print',
                    'page' => 'true',
                    'id' => $record->id,
                    'url' => route($route . '.print', $record->id)
                ];
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(
                [
                    'pengajuan',
                    'jenis_aset',
                    'struct',
                    'jumlah_aktiva',
                    'total_aktiva',
                    'status',
                    'updated_by',
                    'action_show',
                    'habis_masa_depresiasi',
                ]
            )
            ->make(true);
    }
}
