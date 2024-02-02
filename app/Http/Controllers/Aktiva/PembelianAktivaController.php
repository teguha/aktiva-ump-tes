<?php

namespace App\Http\Controllers\Aktiva;

use App\Http\Controllers\Controller;
use App\Http\Requests\Aktiva\PembelianAktivaDetailRequest;
use App\Http\Requests\Aktiva\PembelianAktivaRequest;
use App\Http\Requests\Aktiva\PembelianAktivaUpdateRequest;
use App\Models\Aktiva\PembelianAktiva;
use App\Models\Aktiva\PembelianAktivaDetail;
use App\Models\Master\Barang\Vendor;
use App\Models\Master\Org\OrgStruct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PembelianAktivaController extends Controller
{
    protected $module = 'pengajuan-pembelian';
    protected $routes = 'pengajuan-pembelian';
    protected $views  = 'pengajuan-pembelian';
    protected $perms = 'pengajuan-pembelian';
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
                'title' => 'Pembelian Aktiva',
                'breadcrumb' => [
                    'Pembelian Aktiva' => route($this->routes . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $user = auth()->user();
        $records = PembelianAktiva::grid()->filters()->dtGet();

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

            // number_format($record->details->count(), 0, ',', '.'): Jika $record->details ada (tidak null), maka kita menghitung jumlah data dalam koleksi (collection) yang terkait dengan properti details menggunakan count(). Lalu, hasil perhitungan jumlah tersebut diformat dengan number_format() agar angka lebih mudah dibaca dengan tanda titik sebagai pemisah ribuan dan koma sebagai pemisah desimal.

            // Parameter pertama number_format() adalah angka yang akan diformat (jumlah data dalam koleksi), parameter kedua adalah jumlah desimal yang ingin ditampilkan (diatur ke 0 agar tidak ada angka di belakang koma), parameter ketiga adalah karakter untuk pemisah ribuan (diatur ke ,), dan parameter keempat adalah karakter untuk pemisah desimal (diatur ke .).
           
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
            ->addColumn('action', function ($record) use ($user) {
                $actions = [];
                if ($record->checkAction('show', $this->perms)) {
                    $actions[] = 'type:show|page:true';
                }
                if ($record->checkAction('edit', $this->perms)) {
                    $actions[] = 'type:edit';
                }
                if ($record->checkAction('edit', $this->perms)) {
                    $actions[] = 'type:show|id:' . $record->id . '|label:Detail|icon: fa fa-plus text-info|page:true|url:/pengajuan-pembelian/' . $record->id . '/detail';
                }
                if ($record->checkAction('revision', $this->perms)) {
                    $actions[] = 'type:edit|id:' . $record->id . '|page:true|label:Revisi|url:/pengajuan-pembelian/pengajuan/' . $record->id . '/edit';
                }
                if ($record->checkAction('approval', $this->perms)) {
                    $actions[] = 'type:authorization|id:' . $record->id . '|page:true';
                }
                if ($record->checkAction('delete', $this->perms)) {
                    $actions[] = [
                        'type' => 'delete',
                        'id' => $record->id,
                        'attrs' => 'data-confirm-text="' . __('Hapus Pengajuan Pembelian Aktiva') . ' ' . $record->code . '?"',
                    ];
                }
                if ($record->checkAction('print', $this->perms)) {
                    $actions[] = [
                        'type' => 'print',
                        'page' => 'true',
                        'id' => $record->id,
                        'url' => route($this->routes . '.print', $record->id)
                    ];
                }
                if ($record->checkAction('tracking', $this->perms)) {
                    $actions[] = 'type:tracking';
                }

                if ($record->checkAction('history', $this->perms)) {
                    $actions[] = 'type:history';
                }
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
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);

        return $this->render($this->views . '.index');
    }

    public function create()
    {
        $data = $this->returnDataForm();
        $struct = auth()->user()->position->location ?? null;
        return $this->render($this->views . '.create', compact('data', 'struct'));
    }

    public function store(PembelianAktivaRequest $request)
    {
        $record = new PembelianAktiva;
        return $record->handleStoreOrUpdate($request);
    }

    public function edit(PembelianAktiva $record)
    {
        $struct = auth()->user()->position->location ?? null;
        return $this->render($this->views . '.edit', compact('record', 'struct'));
    }

    public function updateSummary($id, PembelianAktivaRequest $request)
    {
        $record = PembelianAktiva::find($id);
        return $record->handleStoreOrUpdate($request);
    }

    public function show(PembelianAktiva $record)
    {
        $data = $this->returnDataForm(); //return formulir modal
        $jenis_aset = "tangible";
        if ($record->aset) {
            $jenis_aset = $record->aset->jenis_aset ?? "tangible";
            if ($jenis_aset == "intangible") {
                $record_detail = $record->intangibleAsset;
            } else {
                $record_detail = $record->tangibleAsset;
            }
        } else {
            $record_detail = null;
        }
        $data['jenis_aset'] = $jenis_aset;
        $data['record'] = $record;
        $data['record_detail'] = $record_detail;
        $data['page_action'] = "show";
        $breadcrump = "Detil";
        if ($record->status == "revision") {
            $breadcrump = "Revisi";
        }
        
        $this->prepare(
            [
                'perms' => $this->perms,
                'permission' => $this->perms . '.edit',
                'routes' => ($this->routes),
                'title' => 'Pembelian Aktiva',
                'breadcrumb' => [
                    'Pembelian Aktiva' => route($this->routes . '.index'),
                    $breadcrump => route($this->routes . '.edit', $record->id),
                ],
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_2' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:name|label:Nama Aktiva|className:text-center'),
                        $this->makeColumn('name:merk|label:Merk|className:text-center'),
                        $this->makeColumn('name:no_seri|label:No Seri|className:text-center'),
                        $this->makeColumn('name:jmlh_unit|label:Jumlah Unit|className:text-center'),
                        $this->makeColumn('name:total_harga|label:Total Harga|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action_show|label:Aksi|width:60px'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.detail', $data);
    }

    public function detail(PembelianAktiva $record)
    {
        $data = $this->returnDataForm();
        $jenis_aset = "tangible";
        if ($record->aset) {
            $jenis_aset = $record->aset->jenis_aset ?? "tangible";
            if ($jenis_aset == "intangible") {
                $record_detail = $record->intangibleAsset;
            } else {
                $record_detail = $record->tangibleAsset;
            }
        } else {
            $record_detail = null;
        }
        $data['jenis_aset'] = $jenis_aset;
        $data['record'] = $record;
        $data['record_detail'] = $record_detail;
        $data['page_action'] = "detail";
        $breadcrump = "Detil";
        if ($record->status == "revision") {
            $breadcrump = "Revisi";
        }
        
        $this->prepare(
            [
                'perms' => $this->perms,
                'permission' => $this->perms . '.edit',
                'routes' => ($this->routes),
                'title' => 'Pembelian Aktiva',
                'breadcrumb' => [
                    'Pembelian Aktiva' => route($this->routes . '.index'),
                    $breadcrump => route($this->routes . '.edit', $record->id),
                ],
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_2' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:name|label:Nama Aktiva|className:text-center'),
                        $this->makeColumn('name:merk|label:Merk|className:text-center'),
                        $this->makeColumn('name:no_seri|label:No Seri|className:text-center'),
                        $this->makeColumn('name:jmlh_unit|label:Jumlah Unit|className:text-center'),
                        $this->makeColumn('name:total_harga|label:Total Harga|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.detail', $data);
    }

    public function detailGrid(PembelianAktiva $record)
    {
        $user = auth()->user();
        $records = PembelianAktivaDetail::with(['pengajuan'])
            ->whereHas(
                'pengajuan',
                function ($q) use ($record) {
                    $q->where('pengajuan_pembelian_id', $record->id);
                }
            )->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC")
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($detail) {
                    return request()->start;
                }
            )
            ->addColumn(
                'name',
                function ($detail) {
                    return $detail->nama_aktiva ?? '';
                }
            )
            ->addColumn(
                'merk',
                function ($detail) {
                    return $detail->merk ?? '';
                }
            )
            ->addColumn(
                'no_seri',
                function ($detail) {
                    return $detail->no_seri ?? '';
                }
            )
            ->addColumn(
                'jmlh_unit',
                function ($detail) {
                    return number_format($detail->jumlah_unit_pembelian, 0, ",", ".") . ' Unit' ?? '';
                }
            )
            ->addColumn(
                'total_harga',
                function ($detail) {
                    return number_format($detail->total_harga, 0, ",", ".") ?? '';
                }
            )
            ->addColumn(
                'updated_by',
                function ($detail) use ($record) {
                    return $detail->createdByRaw();
                }
            )
            ->addColumn(
                'action_show',
                function ($detail) use ($user, $record) {
                    $actions = [];
                    $actions[] = [
                        'type' => 'show',
                        'url' => route($this->routes . '.detailShow', $detail->id),
                    ];
                    return $this->makeButtonDropdown($actions, $detail->id);
                }
            )
            ->addColumn(
                'action',
                function ($detail) use ($user, $record) {
                    $actions = [];
                    $actions[] = [
                        'type' => 'show',
                        'url' => route($this->routes . '.detailShow', $detail->id),
                    ];
                    $actions[] = [
                        'type' => 'edit',
                        'url' => route($this->routes . '.detailEdit', $detail->id),
                    ];
                    if ($detail->pengajuan->checkAction('edit', $this->perms)) {
                        $actions[] = [
                            'type' => 'delete',
                            'url' => route($this->routes . '.detailDestroy', $detail->id),
                        ];
                    }
                    return $this->makeButtonDropdown($actions, $detail->id);
                }
            )
            ->rawColumns(['action', 'action_show', 'updated_by', 'category', 'description'])
            ->make(true);
    }

    public function detailCreate(PembelianAktiva $record)
    {
        $vendors = Vendor::select("id", "name")->where('status', '=', '2')->get();
        $work_units = OrgStruct::select("id", "name")->get();
        $baseContentReplace = 'base-modal--render';

        return $this->render($this->views . '.detail.create', compact('record', 'baseContentReplace', 'work_units', 'vendors'));
    }

    public function detailStore(PembelianAktivaDetailRequest $request, PembelianAktiva $record)
    {
        $detail = new PembelianAktivaDetail;
        return $record->handleDetailStoreOrUpdate($request, $detail);
    }

    public function detailEdit(PembelianAktivaDetail $detail)
    {
        $record = $detail->pengajuan;
        $vendors = Vendor::select("id", "name")->where('status', '=', '2')->get();
        $work_units = OrgStruct::select("id", "name")->get();
        $baseContentReplace = 'base-modal--render';
        return $this->render($this->views . '.detail.edit', compact('record', 'detail', 'baseContentReplace', 'work_units', 'vendors'));
    }

    public function detailUpdate(PembelianAktivaDetailRequest $request, PembelianAktivaDetail $detail)
    {
        $record = $detail->pengajuan;
        return $record->handleDetailStoreOrUpdate($request, $detail);
    }

    public function detailShow(PembelianAktivaDetail $detail)
    {
        $record = $detail->pengajuan;
        $vendors = Vendor::select("id", "name")->where('status', '=', '2')->get();
        $work_units = OrgStruct::select("id", "name")->get();
        $baseContentReplace = 'base-modal--render';

        // perhitungan
        $tableData = [];
        // Perhitungan depresiasi dan amortisasi
        if ($detail->jenis_asset == "tangible") {
            $month = (int) $detail->tgl_mulai_depresiasi->format('m');
            $yearStart = (int) $detail->tgl_mulai_depresiasi->format('Y');
            $masa_penggunaan = $month > 1 ? $detail->masa_penggunaan + 1 : $detail->masa_penggunaan;
            $harga_per_unit = (int) $detail->harga_per_unit;

            for ($i = 0; $i < $masa_penggunaan; $i++) {
                $tableData[] = [
                    'no' => $i + 1,
                    'tahun' => $yearStart + $i,
                    'masa_manfaat' => $detail->calculateMasaManfaat($i, $masa_penggunaan - 1, $masa_penggunaan, $month),
                    'nilai_buku' =>  number_format($detail->calculateNilaiBuku($i, $masa_penggunaan - 1, $harga_per_unit, $masa_penggunaan, $month), 0, ",", "."),
                    'depresiasi_per_bulan' => number_format($detail->calculateDepresiasiPerBulan($i, $masa_penggunaan - 1, $harga_per_unit, $masa_penggunaan, $month), 0, ",", "."),
                    'depresiasi' => number_format($detail->calculateDepresiasi($i, $masa_penggunaan - 1, $harga_per_unit, $masa_penggunaan, $month), 0, ",", "."),
                ];
            }
        } else if ($detail->jenis_asset == "intangible") {
            $month = (int) $detail->tgl_mulai_amortisasi->format('m');
            $yearStart = (int) $detail->tgl_mulai_amortisasi->format('Y');
            $masa_penggunaan = $month > 1 ? $detail->masa_penggunaan + 1 : $detail->masa_penggunaan;
            $harga_per_unit = (int) $detail->harga_per_unit;

            for ($i = 0; $i < $masa_penggunaan; $i++) {
                $tableData[] = [
                    'no' => $i + 1,
                    'tahun' => $yearStart + $i,
                    'masa_manfaat' => $detail->calculateMasaManfaat($i, $masa_penggunaan - 1, $masa_penggunaan, $month),
                    'nilai_buku' =>  number_format($detail->calculateNilaiBuku($i, $masa_penggunaan - 1, $harga_per_unit, $masa_penggunaan, $month), 0, ",", "."),
                    'amortisasi_per_bulan' => number_format($detail->calculateAmortisasiPerBulan($i, $masa_penggunaan - 1, $harga_per_unit, $masa_penggunaan, $month), 0, ",", "."),
                    'amortisasi' => number_format($detail->calculateAmortisasi($i, $masa_penggunaan - 1, $harga_per_unit, $masa_penggunaan, $month), 0, ",", "."),
                ];
            }
        }
        return $this->render($this->views . '.detail.show', compact('record', 'detail', 'baseContentReplace', 'work_units', 'vendors', 'tableData', 'month'));
    }

    public function detailDestroy(PembelianAktivaDetail $detail)
    {
        $record = $detail->pengajuan;
        return $record->handleDetailDestroy($detail);
    }

    public function showBackup(PembelianAktiva $record)
    {
        $data = $this->returnDataForm();
        $jenis_aset = "tangible";
        if ($record->aset) {
            $jenis_aset = $record->aset->jenis_aset ?? "tangible";
            if ($jenis_aset == "intangible") {
                $record_detail = $record->intangibleAsset;
            } else {
                $record_detail = $record->tangibleAsset;
            }
        } else {
            $record_detail = null;
        }
        $data['jenis_aset'] = $jenis_aset;
        $data['record'] = $record;
        $data['record_detail'] = $record_detail;
        $data['page_action'] = "show";
        $this->prepare(
            [
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'Pembelian Aktiva',
                'breadcrumb' => [
                    'Pembelian Aktiva' => route($this->routes . '.index'),
                    'Lihat' => route($this->routes . '.show', $record->id),
                ],
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:tahun|label:Tahun|className:text-center'),
                        $this->makeColumn('name:nilai_buku|label:Nilai Buku (Rp)|className:text-center'),
                        $this->makeColumn('name:masa_manfaat|label:Masa Manfaat (Bln)|className:text-center'),
                        $this->makeColumn('name:amortisasi|label:Amortisasi (Rp)|className:text-right'),
                        $this->makeColumn('name:amortisasi_per_bulan|label:Amortisasi per Bulan (Rp/Bln)|className:text-right'),
                    ],
                ],
            ]
        );

        if ($jenis_aset == "tangible" && $record->status == "paid") {
            $month = (int) Carbon::parse($record_detail->tgl_mulai_depresiasi)->month;
            $yearStart = Carbon::parse($record_detail->tgl_mulai_depresiasi)->year;
            $masa_penggunaan = $month > 1 ? $record_detail->masa_penggunaan + 1 : $record_detail->masa_penggunaan;

            $data["tableData"] = [];
            for ($i = 0; $i < $masa_penggunaan; $i++) {
                $data["tableData"][] = [
                    'no' => $i + 1,
                    'tahun' => $yearStart + $i,
                    'masa_manfaat' => $record_detail->calculateMasaManfaat($i, $masa_penggunaan - 1),
                    'nilai_buku' => number_format($record_detail->calculateNilaiBuku($i, $masa_penggunaan - 1), 0, ",", "."),
                    'depresiasi_per_bulan' => number_format($record_detail->calculateDepresiasiPerBulan($i, $masa_penggunaan - 1), 0, ",", "."),
                    'depresiasi' => number_format($record_detail->calculateDepresiasi($i, $masa_penggunaan - 1), 0, ",", "."),
                ];
            }
        } else if ($jenis_aset == "intangible" && $record->status == "paid") {
            $month = (int) Carbon::parse($record_detail->tgl_mulai_amortisasi)->month;
            $yearStart = Carbon::parse($record_detail->tgl_mulai_amortisasi)->year;
            $masa_pakai = ceil($record_detail->masa_pakai / 12);
            $masa_pakai = $month > 1 ? $masa_pakai + 1 : $masa_pakai;

            $data["tableData"] = [];
            for ($i = 0; $i < $masa_pakai; $i++) {
                $data["tableData"][] = [
                    'no' => $i + 1,
                    'tahun' => $yearStart + $i,
                    'masa_manfaat' => $record_detail->calculateMasaManfaat($i, $masa_pakai - 1),
                    'nilai_buku' => number_format($record_detail->calculateNilaiBuku($i, $masa_pakai - 1), 0, ",", "."),
                    'amortisasi_per_bulan' => number_format($record_detail->calculateAmortisasiPerBulan($i, $masa_pakai - 1), 0, ",", "."),
                    'amortisasi' => number_format($record_detail->calculateAmortisasi($i, $masa_pakai - 1), 0, ",", "."),
                ];
            }
        }
        return $this->render($this->views . '.detail', $data);
    }

    public function submit(PembelianAktiva $record)
    {
        $this->prepare(['title' => 'Submit Data']);
        $flowApproval = $record->getFlowApproval($this->module);
        return $this->render($this->views . '.submit', compact('record', 'flowApproval'));
    }

    public function submitSave(PembelianAktiva $record, Request $request)
    {
        return $record->handleSubmitSave($request);
    }

    public function approval(PembelianAktiva $record)
    {
        $data = $this->returnDataForm();
        $data['record'] = $record;
        $data['page_action'] = "approval";
        $breadcrump = "Detil";
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_2' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:name|label:Nama Aktiva|className:text-center'),
                        $this->makeColumn('name:merk|label:Merk|className:text-center'),
                        $this->makeColumn('name:no_seri|label:No Seri|className:text-center'),
                        $this->makeColumn('name:jmlh_unit|label:Jumlah Unit|className:text-center'),
                        $this->makeColumn('name:total_harga|label:Total Harga|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action_show|label:Aksi|width:60px'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.detail', $data);
    }

    public function approve(PembelianAktiva $record, Request $request)
    {
        return $record->handleApprove($request);
    }

    public function reject(PembelianAktiva $record, Request $request)
    {
        if ($request->action == "revision") {
            return $record->handleRevision($request);
        }
        return $record->handleReject($request);
    }

    public function update(PembelianAktiva $record, PembelianAktivaUpdateRequest $request)
    {
        return $record->handleSubmitDetail($request);
    }

    public function destroy(PembelianAktiva $record)
    {
        return $record->handleDestroy();
    }

    public function tracking(PembelianAktiva $record)
    {
        return $this->render('globals.tracking', compact('record'));
    }

    public function print(PembelianAktiva $record)
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

    public function history(PembelianAktiva $record)
    {
        return $this->render('globals.history', compact('record'));
    }

    private function returnDataForm()
    {
        $this->datas['vendors'] = Vendor::select("id", "name")->where('status', '=', '2')->get();
        $this->datas['work_units'] = OrgStruct::select("id", "name")->get();
        $this->datas['baseContentReplace'] = true;
        return $this->datas;
    }
}
