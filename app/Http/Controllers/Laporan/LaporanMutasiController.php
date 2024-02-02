<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\MutasiAktiva\MutasiAktiva;
use App\Support\Base;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;



class LaporanMutasiController extends Controller
{
    protected $module = 'laporan.laporan-mutasi';
    protected $routes = 'laporan.laporan-mutasi';
    protected $views  = 'laporan.laporan-mutasi';
    protected $perms = 'laporan';

    const TYPE = [
        'pengajuan'           => [
            'route' => 'pengajuan-mutasi',
            'scope' => 'gridStatusCompleted',
            'show'  => 'Pengajuan',
        ],
        'realization'           => [
            'route' => 'pelaksanaan-mutasi',
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
                'title' => 'Laporan Mutasi',
                'breadcrumb' => [
                    'Laporan' => route($this->routes . '.index'),
                    'Laporan Mutasi' => route($this->routes . '.index'),
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
                        $this->makeColumn('name:pengajuan|label:Pengajuan|className:text-center'),
                        $this->makeColumn('name:from_struct.name|label:Unit Kerja Asal|className:text-center'),
                        $this->makeColumn('name:to_struct.name|label:Unit Kerja Tujuan|className:text-center'),
                        $this->makeColumn('name:details_count|label:Jumlah Aktiva|className:text-center'),
                        $this->makeColumn('name:status|label:Status|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
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
            $records = MutasiAktiva::$scope()->withCount('details')->filters()->dtGet();
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
                'from_struct.name',
                function ($record) {
                    return $record->fromStruct->name;
                }
            )
            ->addColumn(
                'to_struct.name',
                function ($record) {
                    return $record->toStruct->name;
                }
            )
            ->addColumn(
                'details_count',
                function ($record) {
                    return $record->details_count;
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
            ->addColumn('action', function ($record) use ($user, $request, $route) {
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
                    'action',
                    'habis_masa_depresiasi',
                ]
            )
            ->make(true);
    }
}
