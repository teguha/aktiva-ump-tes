<?php

namespace App\Http\Controllers\PelepasanAktiva;

use App\Http\Controllers\Controller;
use App\Http\Requests\PelepasanAktiva\PelaksanaanPenghapusanRequest;
use App\Models\PelepasanAktiva\PelaksanaanPenghapusanAktiva;
use App\Models\PelepasanAktiva\PenghapusanAktiva;
use App\Support\Base;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class PelaksanaanPenghapusanController extends Controller
{
    protected $module = 'penghapusan.pelaksanaan';
    protected $routes = 'pelepasan-aktiva.pelaksanaan-penghapusan';
    protected $views  = 'pelepasan-aktiva.penghapusan.pelaksanaan';
    protected $perms = 'penghapusan.pelaksanaan';

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'Pelaksanaan Penghapusan',
                'breadcrumb' => [
                    'Penghapusan Aktiva' => route($this->routes . '.index'),
                    'Pelaksanaan Penghapusan' => route($this->routes . '.index'),
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
                    $this->makeColumn('name:struct|label:Unit Kerja|className:text-center'),
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
        $tab = $request->input('tab');
        $user = auth()->user();
        $records = PenghapusanAktiva::with('realization')
            ->grid()
            ->filters()
            ->where('status', 'completed');

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
                'pelaksanaan',
                function ($record) {
                    if (isset($record->realization->code)) {
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
                        'url'   => route('pelepasan-aktiva.pelaksanaan-penghapusan.edit', $record->id),
                    ];
                }
                if ($record->checkAction('realization.edit', $this->perms)) {
                    $actions[] = [
                        'type'  => 'edit',
                        'page'  => 'true',
                        'url'   => route('pelepasan-aktiva.pelaksanaan-penghapusan.edit', $record->id),
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
                    'struct',
                    'pelaksanaan',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }

    public function edit($id){
        $page_action = 'show';
        $penghapusan = PenghapusanAktiva::find($id);
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $penghapusan->id),
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
        return $this->render($this->views . '.edit', compact('page_action', 'penghapusan'));
    }

    public function update(PelaksanaanPenghapusanRequest $request, $id)
    {
        $pelaksanaan = PelaksanaanPenghapusanAktiva::firstOrNew(
            [
                'penghapusan_aktiva_id' => $id
            ]
        );
        $pelaksanaan->save();
        return $pelaksanaan->handleStoreOrUpdate($request);
    }

    public function show(PenghapusanAktiva $record){
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
        return $this->render($this->views . '.show', compact('record'));
    }

    public function destroy(PenghapusanAktiva $record)
    {
        return $record->handleDestroy();
    }
    public function detailGrid(PenghapusanAktiva $penghapusan) {
        return app(PenghapusanAktivaController::class)->detailGrid($penghapusan);
    }

    public function approve(PenghapusanAktiva $record, Request $request){
        return $record->realization->handleApprove($request);
    }

    public function reject(PenghapusanAktiva $record, Request $request)
    {
        return $record->realization->handleReject($request);
    }

    public function tracking(PenghapusanAktiva $record)
    {
        return $this->render('globals.tracking', ['record'=>$record->realization]);
    }

    public function history(PenghapusanAktiva $record)
    {
        return $this->render('globals.history', ['record'=>$record->realization]);
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
