<?php

namespace App\Http\Controllers\Pemeriksaan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemeriksaan\PemeriksaanDetailRequest;
use App\Http\Requests\Pemeriksaan\PemeriksaanRequest;
use App\Models\Master\Barang\Vendor;
use App\Models\Master\Org\OrgStruct;
use App\Models\Pemeriksaan\Pemeriksaan;
use App\Models\Pemeriksaan\PemeriksaanDetail;
use App\Support\Base;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PemeriksaanController extends Controller
{
    protected $module = 'pemeriksaan';
    protected $routes = 'pemeriksaan';
    protected $views  = 'pemeriksaan';
    protected $perms = 'pemeriksaan';
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
                'title' => 'Pemeriksaan Kondisi',
                'breadcrumb' => [
                    'Pemeriksaan Kondisi' => route($this->routes . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $user = auth()->user();
        $records = Pemeriksaan::with(
            [
                'struct',
                'details',
            ]
        )
            ->filters()
            ->dtGet();

        return DataTables::of($records)
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
                if ($record->checkAction('show', $this->perms)) {
                    $actions[] = 'type:show|page:true';
                }
                if ($record->checkAction('edit', $this->perms)) {
                    $actions[] = 'type:edit';
                }
                if ($record->checkAction('edit', $this->perms)) {
                    $actions[] = 'type:show|id:' . $record->id . '|label:Detail|icon: fa fa-plus text-info|page:true|url:/pemeriksaan/' . $record->id . '/detail';
                }
                if ($record->checkAction('revision', $this->perms)) {
                    $actions[] = 'type:edit|id:' . $record->id . '|page:true|label:Revisi|url:/pemeriksaan/pemeriksaan/' . $record->id . '/edit';
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
        ]);

        return $this->render($this->views . '.index');
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(PemeriksaanRequest $request)
    {
        $record = new Pemeriksaan;
        return $record->handleStoreOrUpdate($request);
    }

    public function edit(Pemeriksaan $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(PemeriksaanRequest $request, Pemeriksaan $record)
    {
        return $record->handleStoreOrUpdate($request);
        // return $record->handleSubmitDetail($request);
    }

    public function show(Pemeriksaan $record)
    {
        $page_action = "approval";
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_2' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:code|label:Kode Aktiva|className:text-center'),
                        $this->makeColumn('name:name|label:Nama Aktiva|className:text-center'),
                        // $this->makeColumn('name:merk|label:Merk|className:text-center'),
                        // $this->makeColumn('name:no_seri|label:No Seri|className:text-center'),
                        $this->makeColumn('name:condition|label:Kondisi|className:text-center'),
                        $this->makeColumn('name:description|label:Keterangan|className:text-center'),
                        $this->makeColumn('name:files|label:Lampiran|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action_show|label:Aksi|width:60px'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.detail', compact('record', 'page_action'));
    }

    public function detail(Pemeriksaan $record)
    {
        $page_action = "detail";
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_2' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:code|label:Kode Aktiva|className:text-center'),
                        $this->makeColumn('name:name|label:Nama Aktiva|className:text-center'),
                        // $this->makeColumn('name:merk|label:Merk|className:text-center'),
                        // $this->makeColumn('name:no_seri|label:No Seri|className:text-center'),
                        $this->makeColumn('name:condition|label:Kondisi|className:text-center'),
                        $this->makeColumn('name:description|label:Keterangan|className:text-center'),
                        $this->makeColumn('name:files|label:Lampiran|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.detail', compact('record', 'page_action'));
    }

    public function detailGrid(Pemeriksaan $record)
    {
        $user = auth()->user();
        $records = PemeriksaanDetail::with(
            [
                'pemeriksaan',
                'aktiva.pembelianAktivaDetail',
                'files',
            ]
        )
            ->where(
                'pemeriksaan_id',
                $record->id
            )
            ->dtGet();

        return \DataTables::of($records)
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
                'description',
                function ($detail) {
                    return Base::makeLabel(str_word_count($detail->description) . '. Word', 'primary');
                }
            )
            ->addColumn(
                'files',
                function ($detail) {
                    return Base::makeLabel($detail->files()->count() . '. File', 'primary');
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
                    if ($detail->pemeriksaan->checkAction('edit', $this->perms)) {
                        $actions[] = [
                            'type' => 'delete',
                            'url' => route($this->routes . '.detailDestroy', $detail->id),
                        ];
                    }
                    return $this->makeButtonDropdown($actions, $detail->id);
                }
            )
            ->rawColumns(
                [
                    'description',
                    'files',
                    'action', 'action_show', 'updated_by', 'category'
                ]
            )
            ->make(true);
    }

    public function detailCreate(Pemeriksaan $record)
    {
        $baseContentReplace = 'base-modal--render';
        return $this->render($this->views . '.detail.create', compact('record', 'baseContentReplace'));
    }

    public function detailStore(PemeriksaanDetailRequest $request, Pemeriksaan $record)
    {
        $detail = new PemeriksaanDetail;
        return $record->handleDetailStoreOrUpdate($request, $detail);
    }

    public function detailEdit(PemeriksaanDetail $detail)
    {
        $record = $detail->pemeriksaan;
        $baseContentReplace = 'base-modal--render';
        return $this->render(
            $this->views . '.detail.edit',
            compact('record', 'detail')
        );
    }

    public function detailUpdate(PemeriksaanDetailRequest $request, PemeriksaanDetail $detail)
    {
        $record = $detail->pemeriksaan;
        return $record->handleDetailStoreOrUpdate($request, $detail);
    }

    public function detailShow(PemeriksaanDetail $detail)
    {
        $record = $detail->pemeriksaan;

        return $this->render($this->views . '.detail.show', compact('record', 'detail'));
    }

    public function detailDestroy(PemeriksaanDetail $detail)
    {
        $record = $detail->pemeriksaan;
        return $record->handleDetailDestroy($detail);
    }

    public function submit(Pemeriksaan $record)
    {
        $this->prepare(['title' => 'Submit Data']);
        $flowApproval = $record->getFlowApproval($this->module);
        return $this->render($this->views . '.submit', compact('record', 'flowApproval'));
    }

    public function submitSave(Pemeriksaan $record, Request $request)
    {
        return $record->handleSubmitSave($request);
    }

    public function approval(Pemeriksaan $record)
    {
        $page_action = "approval";
        $breadcrump = "Detil";
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_2' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:code|label:Kode Aktiva|className:text-center'),
                        $this->makeColumn('name:name|label:Nama Aktiva|className:text-center'),
                        // $this->makeColumn('name:merk|label:Merk|className:text-center'),
                        // $this->makeColumn('name:no_seri|label:No Seri|className:text-center'),
                        $this->makeColumn('name:condition|label:Kondisi|className:text-center'),
                        $this->makeColumn('name:description|label:Keterangan|className:text-center'),
                        $this->makeColumn('name:files|label:Lampiran|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action_show|label:Aksi|width:60px'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.detail', compact('record', 'page_action'));
    }

    public function approve(Pemeriksaan $record, Request $request)
    {
        return $record->handleApprove($request);
    }

    public function reject(Pemeriksaan $record, Request $request)
    {
        if ($request->action == "revision") {
            return $record->handleRevision($request);
        }
        return $record->handleReject($request);
    }

    public function destroy(Pemeriksaan $record)
    {
        return $record->handleDestroy();
    }

    public function tracking(Pemeriksaan $record)
    {
        return $this->render('globals.tracking', compact('record'));
    }

    public function print(Pemeriksaan $record)
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

    public function history(Pemeriksaan $record)
    {
        return $this->render('globals.history', compact('record'));
    }
}
