<?php

namespace App\Http\Controllers\Aktiva;

use App\Http\Controllers\Controller;
use App\Http\Requests\Aktiva\PembelianAktivaDetailRequest;
use App\Http\Requests\Aktiva\PembelianAktivaRequest;
use App\Http\Requests\Aktiva\PembelianAktivaUpdateRequest;
use App\Models\Aktiva\Aktiva;
use App\Models\Aktiva\PembelianAktiva;
use App\Models\Aktiva\PembelianAktivaDetail;
use App\Models\Master\Barang\TipeBarang;
use App\Models\Master\Barang\Vendor;
use App\Models\Master\Org\OrgStruct;
use App\Models\MutasiAktiva\MutasiAktivaDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AktivaController extends Controller
{
    protected $module = 'aktiva';
    protected $routes = 'aktiva';
    protected $views  = 'aktiva';
    protected $perms = 'aktiva';
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
                'title' => 'Aktiva',
                'breadcrumb' => [
                    'Aktiva' => route($this->routes . '.index'),
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $user = auth()->user();
        $records = Aktiva::with('pembelianAktivaDetail.pengajuan')
            ->when(
                $date_start = $request->date_start,
                function ($q) use ($date_start) {
                    $q->whereHas(
                        'pembelianAktivaDetail',
                        function ($q) use ($date_start) {
                            $q->where(
                                'tgl_pembelian',
                                '>=',
                                Carbon::createFromFormat('d/m/Y', $date_start)->format('Y-m-d')
                            );
                        }
                    );
                }
            )
            ->when(
                $date_end = $request->date_end,
                function ($q) use ($date_end) {
                    $q->whereHas(
                        'pembelianAktivaDetail',
                        function ($q) use ($date_end) {
                            $q->where(
                                'tgl_pembelian',
                                '<=',
                                Carbon::createFromFormat('d/m/Y', $date_end)->format('Y-m-d')
                            );
                        }
                    );
                }
            )
            ->dtGet();

        return DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'nama_aktiva',
                function ($record) {
                    return $record->code . '<br>' . $record->pembelianAktivaDetail->nama_aktiva;
                }
            )
            ->addColumn(
                'merk',
                function ($record) {
                    return $record->pembelianAktivaDetail->merk;
                }
            )
            ->addColumn(
                'no_seri',
                function ($record) {
                    return $record->pembelianAktivaDetail->no_seri;
                }
            )
            ->addColumn(
                'harga_per_unit',
                function ($record) {
                    return 'Rp. ' . number_format($record->pembelianAktivaDetail->harga_per_unit, 0, ',', '.');
                }
            )
            ->addColumn(
                'vendor.name',
                function ($record) {
                    return $record->pembelianAktivaDetail->vendor->name;
                }
            )
            ->addColumn(
                'struct.name',
                function ($record) {
                    return $record->struct->name;
                }
            )
            ->addColumn(
                'pengajuan',
                function ($record) {
                    return $record->pembelianAktivaDetail->pengajuan->code . "<br>"
                        . $record->pembelianAktivaDetail->pengajuan->date->format('d/m/Y');
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
            ->addColumn('action', function ($record) use ($user) {
                $actions = [];
                if ($record->checkAction('show', $this->perms)) {
                    $actions[] = 'type:show|page:true';
                }
                if ($record->checkAction('edit', $this->perms)) {
                    $actions[] = 'type:edit';
                }
                if ($record->checkAction('print', $this->perms)) {
                    $actions[] = [
                        'type' => 'print',
                        'page' => 'true',
                        'id' => $record->id,
                        'url' => route($this->routes . '.print', $record->id)
                    ];
                }

                // if ($record->checkAction('history', $this->perms)) {
                //     $actions[] = 'type:history';
                // }
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(
                [
                    'pengajuan',
                    'struct',
                    'nama_aktiva',
                    'status',
                    'updated_by',
                    'action',
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
                    $this->makeColumn('name:nama_aktiva|label:Nama Aktiva|className:text-center'),
                    $this->makeColumn('name:merk|label:Merk|className:text-center'),
                    $this->makeColumn('name:no_seri|label:No Seri|className:text-center'),
                    $this->makeColumn('name:harga_per_unit|label:Harga Satuan|className:text-center'),
                    $this->makeColumn('name:vendor.name|label:Vendor|className:text-center'),
                    $this->makeColumn('name:struct.name|label:Unit Kerja|className:text-center'),
                    $this->makeColumn('name:pengajuan|label:Pengajuan Pembelian|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);

        return $this->render($this->views . '.index');
    }

    public function edit(Aktiva $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function show(Aktiva $record)
    {
        // perhitungan
        $tableData = [];
        // Perhitungan depresiasi dan amortisasi
        if ($record->pembelianAktivaDetail->jenis_asset == "tangible") {
            $month = (int) $record->pembelianAktivaDetail->tgl_mulai_depresiasi->format('m');
            $yearStart = (int) $record->pembelianAktivaDetail->tgl_mulai_depresiasi->format('Y');
            $masa_penggunaan = $month > 1 ? $record->pembelianAktivaDetail->masa_penggunaan + 1 : $record->pembelianAktivaDetail->masa_penggunaan;
            $harga_per_unit = (int) $record->pembelianAktivaDetail->harga_per_unit;

            for ($i = 0; $i < $masa_penggunaan; $i++) {
                $tableData[] = [
                    'no' => $i + 1,
                    'tahun' => $yearStart + $i,
                    'masa_manfaat' => $record->pembelianAktivaDetail->calculateMasaManfaat($i, $masa_penggunaan - 1, $masa_penggunaan, $month),
                    'nilai_buku' =>  number_format($record->pembelianAktivaDetail->calculateNilaiBuku($i, $masa_penggunaan - 1, $harga_per_unit, $masa_penggunaan, $month), 0, ",", "."),
                    'depresiasi_per_bulan' => number_format($record->pembelianAktivaDetail->calculateDepresiasiPerBulan($i, $masa_penggunaan - 1, $harga_per_unit, $masa_penggunaan, $month), 0, ",", "."),
                    'depresiasi' => number_format($record->pembelianAktivaDetail->calculateDepresiasi($i, $masa_penggunaan - 1, $harga_per_unit, $masa_penggunaan, $month), 0, ",", "."),
                ];
            }
        } else if ($record->pembelianAktivaDetail->jenis_asset == "intangible") {
            $month = (int) $record->pembelianAktivaDetail->tgl_mulai_amortisasi->format('m');
            $yearStart = (int) $record->pembelianAktivaDetail->tgl_mulai_amortisasi->format('Y');
            $masa_penggunaan = $month > 1 ? $record->pembelianAktivaDetail->masa_penggunaan + 1 : $record->pembelianAktivaDetail->masa_penggunaan;
            $harga_per_unit = (int) $record->pembelianAktivaDetail->harga_per_unit;

            for ($i = 0; $i < $masa_penggunaan; $i++) {
                $tableData[] = [
                    'no' => $i + 1,
                    'tahun' => $yearStart + $i,
                    'masa_manfaat' => $record->pembelianAktivaDetail->calculateMasaManfaat($i, $masa_penggunaan - 1, $masa_penggunaan, $month),
                    'nilai_buku' =>  number_format($record->pembelianAktivaDetail->calculateNilaiBuku($i, $masa_penggunaan - 1, $harga_per_unit, $masa_penggunaan, $month), 0, ",", "."),
                    'amortisasi_per_bulan' => number_format($record->pembelianAktivaDetail->calculateAmortisasiPerBulan($i, $masa_penggunaan - 1, $harga_per_unit, $masa_penggunaan, $month), 0, ",", "."),
                    'amortisasi' => number_format($record->pembelianAktivaDetail->calculateAmortisasi($i, $masa_penggunaan - 1, $harga_per_unit, $masa_penggunaan, $month), 0, ",", "."),
                ];
            }
        }
        $this->prepare([
            'tableStruct' => [
                'url' => route($this->routes . '.mutasiGrid', $record->id),
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:pengajuan|label:Pengajuan|className:text-center'),
                    $this->makeColumn('name:from_struct.name|label:Unit Kerja Asal|className:text-center'),
                    $this->makeColumn('name:to_struct.name|label:Unit Kerja Tujuan|className:text-center'),
                    $this->makeColumn('name:status|label:Status|className:text-center'),
                ],
            ],
        ]);
        return $this->render($this->views . '.show', compact('record', 'tableData'));
    }

    public function update(Aktiva $record, AktivaUpdateRequest $request)
    {
        return $record->handleSubmitDetail($request);
    }
    public function print(Aktiva $record)
    {
        $title = $this->prepared('title');
        $module = $this->prepared('module');
        $pdf = \PDF::loadView(
            $this->views . '.print',
            compact('title', 'module', 'record')
        )
            ->setPaper('a4', 'portrait');
        return $pdf->stream(date('Y-m-d') . ' ' . $title . '.pdf');
    }

    public function history(Aktiva $record)
    {
        return $this->render('globals.history', compact('record'));
    }

    public function mutasiGrid(Aktiva $record)
    {
        $user = auth()->user();
        $records = MutasiAktivaDetail::with(['pengajuan.fromStruct', 'pengajuan.toStruct'])
            ->where('aktiva_id', $record->id)
            ->dtGet();

        return DataTables::of($records)
            ->addColumn(
                'num',
                function ($detail) {
                    return request()->start;
                }
            )
            ->addColumn(
                'pengajuan',
                function ($detail) {
                    return $detail->pengajuan->code . '<br>' . $detail->pengajuan->date->format('d/m/Y');
                }
            )
            ->addColumn(
                'from_struct.name',
                function ($detail) {
                    return $detail->pengajuan->fromStruct->name;
                }
            )
            ->addColumn(
                'to_struct.name',
                function ($detail) {
                    return $detail->pengajuan->toStruct->name;
                }
            )
            ->addColumn(
                'status',
                function ($detail) {
                    return $detail->pengajuan->labelStatus();
                }
            )
            ->rawColumns(
                [
                    'pengajuan',
                    'from_struct.name',
                    'to_struct.name',
                    'status',
                ]
            )
            ->make(true);
    }
}
