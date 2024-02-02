<?php

namespace App\Http\Controllers\Laporan;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Models\Globals\Approval;
use App\Models\Master\Org\OrgStruct;
use App\Models\Sgu\PengajuanSgu;
use App\Models\Traits\Utilities;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanSguController extends Controller
{
    //
    use Utilities;

    protected $module = 'laporan.laporan-sgu';
    protected $routes = 'laporan.laporan-sgu';
    protected $views  = 'laporan.laporan-sgu';
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
                'title' => 'Laporan SGU',
                'breadcrumb' => [
                    'Laporan' => route($this->routes . '.index'),
                    'PembelianAktiva' => route($this->routes . '.index'),
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
                        $this->makeColumn('name:pengajuan_sgu|label:Pengajuan SGU|className:text-center'),
                        $this->makeColumn('name:work_unit|label:Unit Kerja|className:text-center'),
                        $this->makeColumn('name:rent_location|label:Lokasi Sewa|className:text-left'),
                        $this->makeColumn('name:pembayaran|label:Pembayaran|className:text-center'),
                        $this->makeColumn('name:rent_time_cost|label:Waktu & Harga|className:text-center'),
                        $this->makeColumn('name:status'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action_show|label:Aksi'),
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
        $records = PengajuanSgu::gridStatusCompleted()->filters()->dtGet();

        $this->prepare(
            [
                'routes' => 'sgu',
            ]
        );

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'pengajuan_sgu',
                function ($record) {
                    return $record->code . "<br>" . $record->submission_date->format('d/m/Y');
                }
            )
            ->addColumn('pembayaran', function ($record) {
                if($record->payment_scheme == 'termin'){
                    return '<span class="badge badge-warning text-white">' . ucwords($record->payment_scheme) . '</span>';
                }else{
                    return '<span class="badge badge-primary text-white">' . strtoupper($record->payment_scheme) . '</span>';

                }
            })
            ->addColumn(
                'work_unit',
                function ($record) {
                    return $record->workUnit->name ?? '-';
                }
            )
            ->addColumn(
                'rent_time_cost',
                function ($record) {
                    return $record->rent_cost ? $record->rent_time_period . " Bulan <br>" . number_format($record->rent_cost, 0, ',', '.') : '-';
                }
            )
            ->addColumn('status', function ($record) {
                return $record->status;
            })
            ->addColumn('updated_by', function ($record) {
                return $record->createdByRaw();
            })
            ->addColumn('action_show', function ($record) use ($user) {
                $actions = [];
                $actions[] = [
                    'type' => 'print',
                    'page' => 'true',
                    'id' => $record->id,
                    'url' => route('sgu.print', $record->id)
                ];
                return $this->makeButtonDropdown($actions);
            })
            ->rawColumns(['pengajuan_sgu', 'pembayaran', 'submission_date','work_unit','status','action_show','rent_time_cost', 'updated_by'])
            ->make(true);
    }
}
