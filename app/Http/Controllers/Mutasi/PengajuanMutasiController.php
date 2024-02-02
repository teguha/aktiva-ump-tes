<?php

namespace App\Http\Controllers\Mutasi;

use App\Http\Controllers\Controller;
use App\Http\Requests\MutasiAktiva\MutasiAktivaDetailRequest;
use App\Http\Requests\MutasiAktiva\MutasiAktivaRequest;
use App\Http\Requests\MutasiAktiva\MutasiAktivaSimpanRequest;
use App\Models\MutasiAktiva\MutasiAktiva;
use App\Models\MutasiAktiva\MutasiAktivaDetail;
use App\Models\PengajuanPembelian\PengajuanPembelianDetail;
use App\Models\Master\Org\OrgStruct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class PengajuanMutasiController extends Controller
{
    protected $module = 'mutasi.pengajuan';
    protected $routes = 'pengajuan-mutasi';
    protected $views  = 'pengajuan-mutasi';
    protected $perms = 'mutasi.pengajuan';
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
                'title' => 'Pengajuan Mutasi',
                'breadcrumb' => [
                    'Mutasi Aktiva' => route($this->routes . '.index'),
                    'Pengajuan Mutasi' => route($this->routes . '.index'),
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
                    $this->makeColumn('name:pengajuan|label:Pengajuan|className:text-center'),
                    $this->makeColumn('name:from_struct.name|label:Unit Kerja Asal|className:text-center'),
                    $this->makeColumn('name:to_struct.name|label:Unit Kerja Tujuan|className:text-center'),
                    $this->makeColumn('name:details_count|label:Jumlah Aktiva|className:text-center'),
                    $this->makeColumn('name:status|label:Status|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);
        return $this->render($this->views . '.index');
    }

    public function grid(Request $request)
    {
        $user = auth()->user();
        $records = MutasiAktiva::grid()->filters()->get();

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
            ->addColumn('action', function ($record) use ($user) {
                $actions = [];
                $actions[] = 'type:show|page:true';
                if ($record->checkAction('edit', $this->perms)) {
                    $actions[] = 'type:edit';
                }
                if ($record->checkAction('edit', $this->perms)) {
                    $actions[] = [
                        'type' => 'edit',
                        'label' => 'Detail',
                        'icon' => 'fa fa-plus text-info',
                        'page' => true,
                        'id' => $record->id,
                        'url' => route($this->routes . '.detail', $record->id),
                    ];
                }
                if ($record->checkAction('approval', $this->perms)) {
                    $actions[] = 'type:authorization|id:' . $record->id . '|page:true';
                }
                if ($record->checkAction('delete', $this->perms)) {
                    $actions[] = [
                        'type' => 'delete',
                        'id' => $record->id,
                        'attrs' => 'data-confirm-text="' . __('Hapus Pengajuan Mutasi') . ' ' . $record->code . '?"',
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
                    'jenis_aset',
                    'from_struct.name',
                    'to_struct.name',
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

    public function create()
    {
        $struct = auth()->user()->position->location ?? null;
        return $this->render($this->views . '.create', compact('struct'));
    }

    public function store(MutasiAktivaRequest $request)
    {
        $record = new MutasiAktiva;
        return $record->handleStoreOrUpdate($request);
    }

    public function edit(MutasiAktiva $record)
    {
        $struct = auth()->user()->position->location ?? null;
        return $this->render($this->views . '.edit', compact('record', 'struct'));
    }

    public function show(MutasiAktiva $record)
    {
        $page_action = "show";
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:code|label:Kode Barang|className:text-center'),
                        $this->makeColumn('name:name|label:Nama Barang|className:text-center'),
                        $this->makeColumn('name:merk|label:Merk|className:text-center'),
                        $this->makeColumn('name:no_seri|label:No Seri|className:text-center'),
                        $this->makeColumn('name:harga_per_unit|label:Harga Satuan|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action_show|label:Aksi'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.show', compact('record', 'page_action'));
    }

    public function detail(MutasiAktiva $record)
    {
        $page_action = "detail";
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:code|label:Kode Barang|className:text-center'),
                        $this->makeColumn('name:name|label:Nama Barang|className:text-center'),
                        $this->makeColumn('name:merk|label:Merk|className:text-center'),
                        $this->makeColumn('name:no_seri|label:No Seri|className:text-center'),
                        $this->makeColumn('name:harga_per_unit|label:Harga Satuan|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.detail', compact('record', 'page_action'));
    }

    public function detailGrid(MutasiAktiva $record)
    {
        $user = auth()->user();
        $records = MutasiAktivaDetail::with(
            [
                'aktiva.pembelianAktivaDetail',
                'pengajuan.fromStruct',
                'pengajuan.toStruct',
            ]
        )
            ->whereHas(
                'pengajuan',
                function ($q) use ($record) {
                    $q->where('pengajuan_id', $record->id);
                }
            )
            ->filters()
            ->dtGet();

        return DataTables::of($records)
            ->addColumn(
                'num',
                function ($detail) {
                    return request()->start;
                }
            )
            ->addColumn(
                'code',
                function ($detail) {
                    return $detail->aktiva->code;
                }
            )
            ->addColumn(
                'name',
                function ($detail) {
                    return $detail->aktiva->pembelianAktivaDetail->nama_aktiva;
                }
            )
            ->addColumn(
                'merk',
                function ($detail) {
                    return $detail->aktiva->pembelianAktivaDetail->merk;
                }
            )
            ->addColumn(
                'no_seri',
                function ($detail) {
                    return $detail->aktiva->pembelianAktivaDetail->no_seri;
                }
            )
            ->addColumn(
                'harga_per_unit',
                function ($detail) {
                    return number_format($detail->aktiva->pembelianAktivaDetail->harga_per_unit, 0, ",", ".");
                }
            )
            ->addColumn(
                'vendor',
                function ($detail) {
                    return $detail->aktiva->pembelianAktivaDetail->vendor->name;
                }
            )
            ->addColumn(
                'updated_by',
                function ($detail) use ($record) {
                    return $detail->aktiva->pembelianAktivaDetail->createdByRaw();
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
                    if ($detail->pengajuan->checkAction('detail.delete', $this->perms)) {
                        $actions[] = [
                            'type' => 'delete',
                            'attrs' => 'data-confirm-text="' . __('Hapus Detail Pengajuan Mutasi') . ' ' . $detail->aktiva->pembelianAktivaDetail->nama_aktiva . '?"',
                            'url' => route($this->routes . '.detailDestroy', $detail->id),
                        ];
                    }
                    return $this->makeButtonDropdown($actions, $detail->id);
                }
            )
            ->rawColumns(['action', 'action_show', 'updated_by', 'category', 'description'])
            ->make(true);
    }

    public function detailCreate(MutasiAktiva $record)
    {
        $baseContentReplace = 'base-modal--render';
        return $this->render($this->views . '.detail.create', compact('record', 'baseContentReplace'));
    }

    public function detailStore(MutasiAktivaDetailRequest $request, MutasiAktiva $record)
    {
        return $record->handleDetailStoreOrUpdate($request);
    }

    public function detailShow(MutasiAktivaDetail $detail)
    {
        $record = $detail->pengajuan;
        $this->prepare(['title' => 'Detail Aktiva']);
        return $this->render($this->views . '.detail.show', compact('record', 'detail'));
    }

    public function detailEdit(MutasiAktivaDetail $detail)
    {
        $baseContentReplace = 'base-modal--render';
        $record = $detail->pengajuan;

        return $this->render($this->views . '.detail.edit', compact('record', 'baseContentReplace', 'detail'));
    }

    public function detailUpdate(MutasiAktivaDetailRequest $request, MutasiAktivaDetail $detail)
    {
        $record = $detail->pengajuan;
        return $record->handleDetailStoreOrUpdate($request, $detail);
    }

    public function detailDestroy(MutasiAktivaDetail $detail)
    {
        $record = $detail->pengajuan;
        return $record->handleDetailDestroy($detail);
    }

    public function destroy(MutasiAktiva $record)
    {
        return $record->handleDestroy();
    }

    public function submit(MutasiAktiva $record)
    {
        $this->prepare(['title' => 'Submit Data']);
        $flowApproval = $record->getFlowApproval($this->module);
        return $this->render($this->views . '.submit', compact('record', 'flowApproval'));
    }

    public function submitSave(MutasiAktiva $record, Request $request)
    {
        return $record->handleSubmitSave($request);
    }

    public function update(MutasiAktiva $record, MutasiAktivaSimpanRequest $request)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function approval(MutasiAktiva $record)
    {

        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:code|label:Kode Barang|className:text-center'),
                        $this->makeColumn('name:name|label:Nama Barang|className:text-center'),
                        $this->makeColumn('name:merk|label:Merk|className:text-center'),
                        $this->makeColumn('name:no_seri|label:No Seri|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action_show|label:Aksi'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.approval', compact('record'));
    }

    public function approve(MutasiAktiva $record, Request $request)
    {
        return $record->handleApprove($request);
    }

    public function reject(MutasiAktiva $record, Request $request)
    {
        if ($request->action == "revision") {
            return $record->handleRevision($request);
        }
        return $record->handleReject($request);
    }

    public function tracking(MutasiAktiva $record)
    {
        return $this->render('globals.tracking', compact('record'));
    }

    public function history(MutasiAktiva $record)
    {
        return $this->render('globals.history', compact('record'));
    }

    public function print(MutasiAktiva $record)
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
}
