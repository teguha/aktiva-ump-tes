<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Aktiva\Aktiva;
use App\Models\Globals\Menu;
use App\Models\Master\Asset\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;


class MonitoringAktivaController extends Controller
{
    protected $module = 'monitoring.monitoring';
    protected $routes = 'monitoring.monitoring-aktiva';
    protected $views  = 'monitoring.monitoring-aktiva';
    protected $perms = 'monitoring';
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
                'title' => 'Monitoring Aktiva',
                'breadcrumb' => [
                    'Monitoring' => route($this->routes . '.index'),
                    'Monitoring Aktiva' => route($this->routes . '.index'),
                ]
            ]
        );
        $this->datas['flowApproval']  = Menu::firstWhere('module', $this->module);
    }

    public function grid()
    {
        $user = auth()->user();
        $records = Aktiva::dtGet();

        return DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'id_asset',
                function ($record) {
                    return $record->code ?? '';
                }
            )
            ->addColumn(
                'jumlah_unit',
                function ($record) {
                    return $record->jumlah_unit_pembelian . ' Unit' ?? '';
                }
            )
            ->addColumn(
                'lokasi_asset',
                function ($record) {
                    return $record->struct->name ?? '';
                }
            )
            ->addColumn(
                'sisa_penggunaan',
                function ($record) {
                    $masa_sisa = '-'; // Nilai default jika masa sisa tidak tersedia
                    if($record->jenis_asset == "intangible"){
                        $masa_sisa = $record->habis_masa_amortisasi ?? null;
                    }else{
                        $masa_sisa = $record->habis_masa_depresiasi ?? null;
                    }
                    if ($masa_sisa !== null) {
                        $depresiasi = strtotime($masa_sisa);
                        $perolehan = strtotime($record->date);
                        $datediff = $depresiasi - $perolehan;

                        return round($datediff / (60 * 60 * 24)) . ' Hari';
                    }

                    return $masa_sisa; // Mengembalikan nilai default jika masa sisa tidak tersedia
                }
            )
            ->addColumn(
                'nilai_buku',
                function ($record) {
                    if ($record->jenis_asset == "tangible") {
                        $month = (int) $record->tgl_mulai_depresiasi->format('m');
                        $yearStart = (int) $record->tgl_mulai_depresiasi->format('Y');
                        $yearDepresiasi = (int) $record->habis_masa_depresiasi->format('Y');
                        $masa_penggunaan = $month > 1 ? $record->masa_penggunaan + 1 : $record->masa_penggunaan;
                        $harga_per_unit = (int) $record->harga_per_unit;
                        $currentValue = $masa_penggunaan - ($yearDepresiasi - $yearStart) - 1;

                        return number_format($record->calculateNilaiBuku($currentValue, $masa_penggunaan - 1, $harga_per_unit, $masa_penggunaan, $month), 0, ",", ".");

                    } else if ($record->jenis_asset == "intangible") {
                        $month = (int) $record->tgl_mulai_amortisasi->format('m');
                        $yearStart = (int) $record->tgl_mulai_amortisasi->format('Y');
                        $masa_penggunaan = $month > 1 ? $record->masa_penggunaan + 1 : $record->masa_penggunaan;
                        $harga_per_unit = (int) $record->harga_per_unit;
                        $masa_pakai = ceil($record->masa_penggunaan / 12);

                        return number_format($record->calculateNilaiBuku($masa_pakai, $masa_penggunaan - 1, $harga_per_unit, $masa_penggunaan, $month), 0, ",", ".");
                    }
                }
            )
            ->addColumn('action', function ($record) use ($user) {
                $checkId = $record->id;
                $checkIdAset = $record->aset->id ?? null;
                $status = $checkIdAset;
                $actions = [];
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(
                [
                    'id_asset',
                    'jenis_asset',
                    'jumlah_unit',
                    'lokasi_asset',
                    'skema_pembayaran',
                    'date',
                    'tgl_habis_depresiasi',
                    'sisa_penggunaan',
                    'harga_perolehan',
                    'nilai_buku',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
            // Fungsi rawColumns() digunakan untuk mendefinisikan kolom-kolom yang berisi data HTML mentah, sehingga DataTables tidak akan menghindari pemrosesan HTML di kolom-kolom tersebut.
            // Terakhir, fungsi make(true) digunakan untuk menghasilkan data JSON yang akan digunakan oleh DataTables untuk menampilkan tabel dengan data yang telah diproses
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:id_asset|label:ID Asset|className:text-center'),
                    $this->makeColumn('name:lokasi_asset|label:Lokasi|className:text-center'),
                    $this->makeColumn('name:sisa_penggunaan|label:Sisa Penggunaan (Hari)|className:text-center'),
                    $this->makeColumn('name:nilai_buku|label:Nilai Buku Saat Ini (Rp)|className:text-center'),
                    $this->makeColumn('name:action|label:Aksi'),
                ],
            ],
        ]);

        return $this->render($this->views . '.index');
    }
}
