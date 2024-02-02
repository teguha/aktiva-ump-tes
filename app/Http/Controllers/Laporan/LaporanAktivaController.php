<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Aktiva\PembelianAktiva;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class LaporanAktivaController extends Controller
{
    protected $module = 'laporan.laporan-aktiva';
    protected $routes = 'laporan.laporan-aktiva';
    protected $views  = 'laporan.laporan-aktiva';
    protected $perms = 'laporan';
    private $datas;

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'Laporan Pembelian Aktiva',
                'breadcrumb' => [
                    'Laporan' => route($this->routes . '.index'),
                    'Pembelian Aktiva' => route($this->routes . '.index'),
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $user = auth()->user();
        $records = PembelianAktiva::gridStatusCompleted()->filters()->dtGet();

        $this->prepare(
            [
                'routes' => 'pengajuan-pembelian',
            ]
        );

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
                    return $record->code . "<br>" . Carbon::parse($record->date)->format('d/m/Y');
                }
            )
            ->addColumn(
                'struct',
                function ($record) {
                    return $record->getStructName();
                }
            )
            ->addColumn(
                'nama_aktiva',
                function ($record) {
                    return $record->itemName() ? $record->itemName() : '-';
                }
            )
            ->addColumn(
                'jumlah_aktiva',
                function ($record) {
                    return $record->details ? number_format($record->details->count(), 0, ',', '.') : "-";
                }
            )
            ->addColumn(
                'skema_pembayaran',
                function ($record) {
                    return $record->getSkemaPembayaran();
                }
            )
            ->addColumn(
                'total_aktiva',
                function ($record) {
                    if ($record->details) {
                        $sum = $record->details->sum(function ($detail) {
                            return $detail->harga_per_unit * $detail->jumlah_unit_pembelian;
                        });
                        return number_format($sum, 0, ',', '.');
                    }
                    return '-';
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
                    // return 6;
                }
            )
            ->addColumn('action_show', function ($record) use ($user) {
                $actions = [];
                $actions[] = [
                    'type' => 'print',
                    'page' => 'true',
                    'id' => $record->id,
                    'url' => route('pengajuan-pembelian.print', $record->id)
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
                    'action_show',
                ]
            )
            ->make(true);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:pengajuan|label:Pengajuan|className:text-center'),
                    $this->makeColumn('name:struct|label:Unit Kerja|className:text-center'),
                    $this->makeColumn('name:skema_pembayaran|label:Pembayaran|className:text-center'),
                    $this->makeColumn('name:jumlah_aktiva|label:Jumlah Aktiva|className:text-center'),
                    $this->makeColumn('name:total_aktiva|label:Total Harga (Rp)|className:text-right'),
                    $this->makeColumn('name:status|label:Status|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action_show|label:Aksi'),
                ],
            ],
        ]);

        return $this->render($this->views . '.index');
    }

}
