<?php

namespace App\Http\Controllers\Laporan;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Models\Globals\Approval;
use App\Models\Pemeriksaan\Pemeriksaan;
use App\Models\Traits\Utilities;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanPemeriksaanController extends Controller
{
    //
    use Utilities;

    protected $module = 'laporan.laporan-pemeriksaan';
    protected $routes = 'laporan.laporan-pemeriksaan';
    protected $views  = 'laporan.laporan-pemeriksaan';
    protected $perms = 'laporan';

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'Laporan Pemeriksaan',
                'breadcrumb' => [
                    'Laporan' => route($this->routes . '.index'),
                    'Pemeriksaan' => route($this->routes . '.index'),
                ]
            ]
        );
    }

    // ------------------------------------------------- //
    // Show and Create Datatables Structure
    // ------------------------------------------------- //
    public function index()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:code|label:Id Pemeriksaan|className:text-center'),
                        $this->makeColumn('name:date|label:Tgl Pemeriksaan|className:text-center'),
                        $this->makeColumn('name:struct|label:Unit Kerja|className:text-center'),
                        $this->makeColumn('name:diperiksa|label:Jml diperiksa|className:text-center'),
                        $this->makeColumn('name:rusak|label:Jml rusak|className:text-center'),
                        $this->makeColumn('name:status|label:Status|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
                ],
            ]
        );

        return $this->render($this->views . '.index');
    }

    // ------------------------------------------------- //
    // Insert "Perngajuan" Datas to Datatable
    // ------------------------------------------------- //
    public function grid(Request $request)
    {
        $user = auth()->user();
        $this->prepare(
            [
                'routes' => 'pemeriksaan',
            ]
        );

        $records = Pemeriksaan::with(
            [
                'struct',
                'details',
            ]
        )   ->gridStatusCompleted()
            ->filters()
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'date',
                function ($record) {
                    return $record->date->format('d/m/Y');
                }
            )
            ->addColumn(
                'struct',
                function ($record) {
                    return $record->struct->name;
                }
            )
            ->addColumn(
                'diperiksa',
                function ($record) {
                    return $record->details ? number_format($record->details->count(), 0, ',', '.') : "-";
                }
            )
            ->addColumn(
                'rusak',
                function ($record) {
                    return $record->details ? number_format($record->details->where('condition', 'Rusak')->count(), 0, ',', '.') : "-";
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
            ->addColumn('action', function ($record) use ($user) {
                $actions = [];
                $actions[] = [
                    'type' => 'print',
                    'page' => 'true',
                    'id' => $record->id,
                    'url' => route('pemeriksaan.print', $record->id)
                ];
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(
                [
                    'pengajuan',
                    'date',
                    'struct',
                    'jumlah_aktiva',
                    'skema_pembayaran',
                    'total_aktiva',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }
}
