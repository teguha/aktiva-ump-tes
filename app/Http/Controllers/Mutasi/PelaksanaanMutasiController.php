<?php

namespace App\Http\Controllers\Mutasi;

use App\Http\Controllers\Controller;
use App\Http\Requests\MutasiAktiva\PelaksanaanMutasiRequest;
use App\Models\MutasiAktiva\MutasiAktiva;
use App\Models\MutasiAktiva\PelaksanaanMutasi;
use App\Support\Base;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class PelaksanaanMutasiController extends Controller
{
    protected $module = 'mutasi.pelaksanaan';
    protected $routes = 'pelaksanaan-mutasi';
    protected $views  = 'pengajuan-mutasi.pelaksanaan';
    protected $perms = 'mutasi.pelaksanaan';
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
                'title' => 'Pelaksanaan Mutasi',
                'breadcrumb' => [
                    'Mutasi Aktiva' => route($this->routes . '.index'),
                    'Pelaksanaan Mutasi' => route($this->routes . '.index'),
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
                    $this->makeColumn('name:pelaksanaan|label:Pelaksanaan|className:text-center'),
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
        $records = MutasiAktiva::gridStatusCompleted()->filters()->get();

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
                'pelaksanaan',
                function ($record) {
                    if (isset($record->realization->date)) {
                        return $record->realization->code . "<br>" . $record->realization->date->format('d/m/Y');
                    }
                    return '';
                }
            )
            ->addColumn(
                'status',
                function ($record) {
                    return $record->labelStatus($record->realization->status ?? 'new');
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) {
                    if ($record->realization) {
                        return $record->realization->createdByRaw();
                    }
                    return '';
                }
            )
            ->addColumn('action', function ($record) use ($user) {
                $actions = [];
                if (($record->realization->status ?? 'new') !== 'new') {
                    $actions[] = 'type:show|page:true';
                }
                if ($record->checkAction('realization.create', $this->perms)) {
                    $actions[] = [
                        'type'  => 'create',
                        'page'  => 'true',
                        'url'   => route($this->routes.'.edit', $record->id),
                    ];
                }
                if ($record->checkAction('realization.edit', $this->perms)) {
                    $actions[] = [
                        'type'  => 'edit',
                        'page'  => 'true',
                        'url'   => route($this->routes.'.edit', $record->id),
                    ];
                }
                if ($record->checkAction('realization.approval', $this->perms)) {
                    $actions[] = [
                        'type'  => 'approval',
                        'id'    => $record->id,
                        'page'  => 'true',
                        'url'   => route($this->routes.'.show', $record->id),
                    ];
                }
                if ($record->checkAction('realization.print', $this->perms)) {
                    $actions[] = [
                        'type' => 'print',
                        'page' => 'true',
                        'id' => $record->id,
                        'url' => route($this->routes . '.print', $record->id)
                    ];
                }
                if ($record->checkAction('realization.tracking', $this->perms)) {
                    $actions[] = 'type:tracking';
                }

                if ($record->checkAction('realization.history', $this->perms)) {
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
                    'pelaksanaan',
                    'status',
                    'updated_by',
                    'action',
                    'habis_masa_depresiasi',
                ]
            )
            ->make(true);
    }

    public function detailGrid(MutasiAktiva $record) {
        return app(PengajuanMutasiController::class)->detailGrid($record);
    }

    public function edit($id){
        $record = MutasiAktiva::find($id);
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
                        $this->makeColumn('name:vendor|label:Vendor|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(MutasiAktiva $record, PelaksanaanMutasiRequest $request)
    {
        return $record->handleRealizationStoreOrUpdate($request);
    }

    public function show(PelaksanaanMutasi $record){
        return $this->render($this->views . '.show', compact('record'));
    }

    public function submit(PelaksanaanMutasi $record)
    {
        $this->prepare(['title' => 'Submit Data']);
        $flowApproval = $record->getFlowApproval($this->module);
        return $this->render($this->views . '.submit', compact('record', 'flowApproval'));
    }

    public function submitSave(PelaksanaanMutasi $record, Request $request)
    {
        return $record->handleSubmitSave($request);
    }

    public function approval(PelaksanaanMutasi $record){

        $this->prepare(
            [
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'routes' => ($this->routes),
                'title' => 'Penghapusan Aktiva',
                'breadcrumb' => [
                    'Penghapusan Aktiva' => route($this->routes . '.index'),
                    'Detail' => route($this->routes . '.show', $record->id),
                ],
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:name|label:Nama Barang|className:text-center'),
                        $this->makeColumn('name:merk|label:Merk|className:text-center'),
                        $this->makeColumn('name:no_seri|label:No Seri|className:text-center'),
                        $this->makeColumn('name:jmlh_unit|label:Jumlah Unit|className:text-center'),
                        $this->makeColumn('name:jmlh_unit_mutasi|label:Jumlah Unit Mutasi|className:text-center'),
                        $this->makeColumn('name:total_harga|label:Total Harga|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action_show|label:Aksi'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.approval', compact('record'));
    }

    public function approve(PelaksanaanMutasi $record, Request $request){
        return $record->handleApprove($request);
    }

    public function reject(PelaksanaanMutasi $record, Request $request)
    {
        if ($request->action == "revision") {
            return $record->handleRevision($request);
        }
        return $record->handleReject($request);
    }

    public function tracking(PelaksanaanMutasi $record)
    {
        return $this->render('globals.tracking', compact('record'));
    }

    public function history(PelaksanaanMutasi $record)
    {
        return $this->render('globals.history', compact('record'));
    }
}
