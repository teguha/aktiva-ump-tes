<?php

namespace App\Http\Controllers\PelepasanAktiva;

use App\Http\Controllers\Controller;
use App\Http\Requests\PelepasanAktiva\PenghapusanAktiva\PenghapusanAktivaDetailRequest;
use App\Http\Requests\PelepasanAktiva\PenghapusanAktiva\PenghapusanAktivaDetailSubmitRequest;
use App\Http\Requests\PelepasanAktiva\PenghapusanAktiva\PenghapusanAktivaRequest;
use App\Models\PelepasanAktiva\PenghapusanAktiva;
use App\Models\PelepasanAktiva\PenghapusanAktivaDetail;
use App\Support\Base;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class PenghapusanAktivaController extends Controller
{
    protected $module = 'penghapusan.pengajuan';
    protected $routes = 'pelepasan-aktiva.penghapusan';
    protected $views  = 'pelepasan-aktiva.penghapusan';
    protected $perms = 'penghapusan.pengajuan';
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
                'title' => 'Pengajuan Penghapusan',
                'breadcrumb' => [
                    'Penghapusan Aktiva' => route($this->routes . '.index'),
                    'Pengajuan Penghapusan' => route($this->routes . '.index'),
                ]
            ]
        );
        // $this->datas['flowApproval']  = Menu::firstWhere('module', $this->module);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:pengajuan|label:Pengajuan|className:text-center'),
                    $this->makeColumn('name:struct|label:Unit Kerja|className:text-center'),
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
        $tab = $request->input('tab');
        $user = auth()->user();
        $records = PenghapusanAktiva::grid()->filters()->get();

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
                if ($record->checkAction('show', $this->perms)) {
                    $actions[] = 'type:show|page:true';
                }
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
                        'attrs' => 'data-confirm-text="' . __('Hapus Pengajuan Penghapusan Aktiva') . ' ' . $record->code . '?"',
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

    public function create()
    {
        $struct = auth()->user()->position->location ?? null;
        return $this->render($this->views . '.create', compact('struct'));
    }

    public function store(PenghapusanAktivaRequest $request)
    {
        $record = new PenghapusanAktiva;
        return $record->handleStoreOrUpdate($request);
    }

    public function edit(PenghapusanAktiva $record)
    {
        $struct = auth()->user()->position->location ?? null;
        return $this->render($this->views . '.edit', compact('record', 'struct'));
    }

    public function updateSummary($id, PenghapusanAktivaRequest $request)
    {
        $record = PenghapusanAktiva::findOrFail($id);
        return $record->handleStoreOrUpdate($request, true);
    }

    public function show(PenghapusanAktiva $record)
    {
        $page_action = "show";
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:code|label:Kode Aktiva|className:text-center'),
                        $this->makeColumn('name:name|label:Nama Aktiva|className:text-center'),
                        $this->makeColumn('name:merk|label:Merk|className:text-center'),
                        $this->makeColumn('name:no_seri|label:No Seri|className:text-center'),
                        $this->makeColumn('name:harga_per_unit|label:Harga Satuan|className:text-center'),
                        $this->makeColumn('name:vendor|label:Vendor|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.detail', compact('record', 'page_action'));
    }

    public function detail(PenghapusanAktiva $record)
    {
        $page_action = "detail";
        $this->prepare(
            [
                'title' => 'Pengajuan Penghapusan Aktiva',
                'breadcrumb' => [
                    'Pengajuan Penghapusan Aktiva' => route($this->routes . '.index'),
                    'Detail' => route($this->routes . '.detail', $record->id),
                ],
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:code|label:Kode Aktiva|className:text-center'),
                        $this->makeColumn('name:name|label:Nama Aktiva|className:text-center'),
                        $this->makeColumn('name:merk|label:Merk|className:text-center'),
                        $this->makeColumn('name:no_seri|label:No Seri|className:text-center'),
                        $this->makeColumn('name:harga_per_unit|label:Harga Satuan|className:text-center'),
                        $this->makeColumn('name:vendor|label:Vendor|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.detail', compact('record', 'page_action'));
    }

    public function detailGrid(PenghapusanAktiva $record)
    {
        $user = auth()->user();
        $records = PenghapusanAktivaDetail::with(['pengajuan', 'aktiva.pembelianAktivaDetail'])
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
                    return Base::makeLabel($detail->aktiva->code, 'danger');
                }
            )
            ->addColumn(
                'name',
                function ($detail) {
                    return $detail->aktiva->pembelianAktivaDetail->nama_aktiva;
                }
            )
            ->addColumn(
                'vendor',
                function ($detail) {
                    return $detail->aktiva->pembelianAktivaDetail->vendor->name;
                }
            )
            ->addColumn(
                'no_seri',
                function ($detail) {
                    return $detail->aktiva->pembelianAktivaDetail->no_seri;
                }
            )
            ->addColumn(
                'merk',
                function ($detail) {
                    return $detail->aktiva->pembelianAktivaDetail->merk;
                }
            )
            ->addColumn(
                'harga_per_unit',
                function ($detail) {
                    return 'Rp. ' . number_format($detail->aktiva->pembelianAktivaDetail->harga_per_unit, 0, '.', ',');
                }
            )
            ->addColumn(
                'total_harga',
                function ($detail) {
                    return number_format($detail->aktiva->pembelianAktivaDetail->total_harga, 0, ",", ".");
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
                    // if ($detail->pengajuan->checkAction('edit', $this->perms)) {
                    //     $actions[] = [
                    //         'type' => 'edit',
                    //         'url' => route($this->routes . '.detailEdit', $detail->id),
                    //     ];
                    // }
                    if ($detail->pengajuan->checkAction('detail.edit', $this->perms)) {
                        $actions[] = [
                            'type' => 'delete',
                            'attrs' => 'data-confirm-text="' . __('Hapus Detail Pengajuan Penghapusan Aktiva') . ' ' . $detail->aktiva->pembelianAktivaDetail->nama_aktiva . '?"',
                            'url' => route($this->routes . '.detailDestroy', $detail->id),
                        ];
                    }
                    return $this->makeButtonDropdown($actions, $detail->id);
                }
            )
            ->rawColumns(
                [
                    'code',
                    'description',
                    'updated_by',
                    'category',
                    'action_show',
                    'action',
                ]
            )
            ->make(true);
    }

    public function detailCreate(PenghapusanAktiva $record)
    {
        $baseContentReplace = 'base-modal--render';
        $this->prepare(
            [
                'title' => 'Detail Penghapusan',
            ]
        );
        return $this->render($this->views . '.detail.create', compact('record', 'baseContentReplace'));
    }

    public function detailStore(PenghapusanAktivaDetailRequest $request, PenghapusanAktiva $record)
    {
        return $record->handleDetailStoreOrUpdate($request);
    }

    public function detailEdit(PenghapusanAktivaDetail $detail)
    {
        $baseContentReplace = 'base-modal--render';
        $record = $detail->pengajuan;

        return $this->render($this->views . '.detail.edit', compact('record', 'baseContentReplace', 'detail'));
    }

    public function detailUpdate(PenghapusanAktivaDetailRequest $request, PenghapusanAktivaDetail $detail)
    {
        $record = $detail->pengajuan;
        return $record->handleDetailStoreOrUpdate($request, $detail);
    }

    public function detailShow(PenghapusanAktivaDetail $detail)
    {
        $record = $detail->pengajuan;
        $baseContentReplace = 'base-modal--render';
        $this->prepare(
            [
                'title' => 'Detail Penghapusan',
            ]
        );
        return $this->render($this->views . '.detail.show', compact('record', 'detail', 'baseContentReplace'));
    }

    public function detailDestroy(PenghapusanAktivaDetail $detail)
    {
        $record = $detail->pengajuan;
        return $record->handleDetailDestroy($detail);
    }

    public function destroy(PenghapusanAktiva $record)
    {
        return $record->handleDestroy();
    }

    public function update(PenghapusanAktiva $record, PenghapusanAktivaDetailSubmitRequest $request)
    {
        return $record->handleSubmitDetail($request);
    }

    public function submit(PenghapusanAktiva $record)
    {
        $this->prepare(['title' => 'Submit Data']);
        $flowApproval = $record->getFlowApproval($this->module);
        return $this->render($this->views . '.submit', compact('record', 'flowApproval'));
    }

    public function submitSave(PenghapusanAktiva $record, Request $request)
    {
        return $record->handleSubmitSave($request);
    }

    public function approval(PenghapusanAktiva $record)
    {

        $page_action = "approval";
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:code|label:Kode Aktiva|className:text-center'),
                        $this->makeColumn('name:name|label:Nama Aktiva|className:text-center'),
                        $this->makeColumn('name:merk|label:Merk|className:text-center'),
                        $this->makeColumn('name:no_seri|label:No Seri|className:text-center'),
                        $this->makeColumn('name:harga_per_unit|label:Harga Satuan|className:text-center'),
                        $this->makeColumn('name:vendor|label:Vendor|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.detail', compact('record', 'page_action'));
    }

    public function approve(PenghapusanAktiva $record, Request $request)
    {
        return $record->handleApprove($request);
    }

    public function reject(PenghapusanAktiva $record, Request $request)
    {
        if ($request->action == "revision") {
            return $record->handleRevision($request);
        }
        return $record->handleReject($request);
    }

    public function history(PenghapusanAktiva $record)
    {
        return $this->render('globals.history', compact('record'));
    }

    public function tracking(PenghapusanAktiva $record)
    {
        return $this->render('globals.tracking', compact('record'));
    }

    public function print(PenghapusanAktiva $record)
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
