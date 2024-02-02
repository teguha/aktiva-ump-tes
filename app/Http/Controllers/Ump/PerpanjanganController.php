<?php

namespace App\Http\Controllers\Ump;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ump\PerpanjanganUmpRequest;
use App\Models\Master\RekeningBank\RekeningBank;
use App\Models\Ump\PerpanjanganUmp;
use App\Models\Ump\PjUmp;
use App\Support\Base;
use Illuminate\Http\Request;

class PerpanjanganController extends Controller
{
    protected $module = 'ump.perpanjangan-ump';
    protected $routes = 'ump.perpanjangan-ump';
    protected $views = 'ump.perpanjangan';
    protected $perms = 'ump.perpanjangan-ump';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms.'.view',
            'title' => 'Perpanjangan UMP',
            'breadcrumb' => [
                'Home' => route('home'),
                'UMP' => '#',
                'Perpanjangan UMP' => route($this->routes.'.index'),
            ]
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:#|className:text-right'),
                    $this->makeColumn('name:code|label:Pengajuan UMP|className:text-center'),
                    $this->makeColumn('name:struct|label:Unit Kerja|className:text-center'),
                    $this->makeColumn('name:nominal|label:Nominal (Rp)|className:text-center'),
                    $this->makeColumn('name:perpanjangan|label:Perpanjangan|className:text-center'),
                    $this->makeColumn('name:status'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);
        return $this->render($this->views.'.index');
    }

    public function grid()
    {
        $user = auth()->user();
        $records = PerpanjanganUmp::with('pengajuanUmp', 'pengajuanUmp.pengajuanSgu', 'pengajuanUmp.aktiva')->grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('#', function ($record) {
                return request()->start;
            })
            ->addColumn('code', function ($record) {
                return $record->pengajuanUmp->code_ump . "<br>" . $record->pengajuanUmp->date_ump->format('d/m/Y');
            })
            ->addColumn('struct', function ($record) {
                return $record->pengajuanUmp->struct ? $record->pengajuanUmp->struct->name : '';
            })
            ->addColumn('nominal', function ($record) {
                return number_format($record->pengajuanUmp->nominal_pembayaran, 0, ',', '.');
            })
            ->addColumn('perpanjangan', function ($record) {
                if($record->id_ump_perpanjangan){
                    return $record->id_ump_perpanjangan . "<br>" . $record->tgl_ump_perpanjangan->format('d/m/Y');
                }
                return "-";
            })
            ->addColumn('status', function ($record) use ($user) {
                return $record->labelStatus($record->status ?? 'new');
            })
            ->addColumn('updated_by', function ($record) {
                if($record->status == Null || $record->status == 'new'){
                    return "";
                }else{
                    return $record->createdByRaw();
                }
            })
            ->addColumn('action', function ($record) use ($user) {
                $actions = [];

                if($record->status == Null || $record->status == 'new'){
                    if ($record->checkAction('edit', $this->perms)) {
                        $actions[] = [
                            'type' => 'edit',
                            'label'=> 'Tambah',
                            'icon' => 'fa fa-plus text-info',
                            'page' => true,
                            'id' => $record->id,
                            'url' => route($this->routes . '.edit', $record->id),
                        ];
                    }
                }else{
                    if ($record->checkAction('show', $this->perms)) {
                        $actions[] = [
                            'type' => 'show',
                            'page' => true,
                            'id' => $record->id,
                            'url' => route($this->routes . '.show', $record->id),
                        ];
                    }
                    if ($record->checkAction('edit', $this->perms)) {
                        $actions[] = [
                            'type' => 'edit',
                            'page' => true,
                            'id' => $record->id,
                            'url' => route($this->routes . '.edit', $record->id),
                        ];
                    }
                    if ($record->checkAction('approval', $this->perms)) {
                        $actions[] = 'type:approval|page:true|label:Otorisasi';
                    }
                    if($record->checkAction('payment', $this->perms)){
                        $actions[] = [
                            'page' => true,
                            'icon' => 'fa fa-edit text-success',
                            'label' => 'Bayar',
                            'url' => route($this->prepared('routes').'.payment', $record)
                        ];
                    }
                    if($record->checkAction('verification', $this->perms)){
                        $actions[] = [
                            'page' => true,
                            'icon' => 'fa fa-edit text-success',
                            'label' => 'Verifikasi',
                            'url' => route($this->prepared('routes').'.verification', $record)
                        ];
                    }
                    if($record->checkAction('confirmation', $this->perms)){
                        $actions[] = [
                            'page' => true,
                            'icon' => 'fa fa-edit text-success',
                            'label' => 'Konfirmasi',
                            'url' => route($this->prepared('routes').'.confirmation', $record)
                        ];
                    }
                    if($record->checkAction('revision', $this->perms)){
                        $actions[] = [
                            'page' => true,
                            'icon' => 'fa fa-edit text-success',
                            'label' => 'Revisi',
                            'url' => route($this->prepared('routes').'.edit', $record)
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
                }

                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(['action','updated_by','status', 'code', 'perpanjangan'])
            ->make(true);
    }

    public function create()
    {
        $this->prepare([
            'tableStruct' => [
                'url'   => route($this->routes.'.gridDetail'),
                'datatable_2' => [
                    $this->makeColumn('name:#|className:text-right'),
                    $this->makeColumn('name:code|label:ID Pengajuan UMP|className:text-center'),
                    $this->makeColumn('name:asset|label:Jenis Asset Nama Asset No Seri|className:text-center'),
                    $this->makeColumn('name:struct|label:Unit Kerja|className:text-center'),
                    $this->makeColumn('name:tgl_pencairan|label:Tgl Pencairan UMP|className:text-center'),
                    $this->makeColumn('name:tgl_jatuh_tempo|label:Tgl Jatuh Tempo UMP|className:text-center'),
                    $this->makeColumn('name:nominal|label:Nominal UMP (Rp)|className:text-right'),
                    $this->makeColumn('name:id_perpanjangan|label:ID Perpanjangan UMP|className:text-center'),
                    $this->makeColumn('name:status'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);
        return $this->render($this->views.'.modal.create');
    }

    public function gridDetail()
    {
        $user = auth()->user();
        $records = PjUmp::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('#', function ($record) {
                return request()->start;
            })
            ->addColumn('code', function ($record) {
                return $record->aktiva->code;
            })
            ->addColumn('asset', function($record){
                $asset = $record->aktiva->tangibleAsset ?? $record->aktiva->intangibleAsset;
                $jenis_asset = $record->aktiva->tangibleAsset ? 'Tangible' : 'Intangible';
                return '<span class="badge badge-primary">'.$jenis_asset.'</span><br>'.$asset->nama_aktiva.'<br>'.$asset->no_seri;
            })
            ->addColumn('struct', function ($record) {
                return $record->aktiva->getStructName();
            })
            ->addColumn('tgl_pencairan', function ($record) {
                return $record->pengajuanUmp->show_tgl_pencairan;
            })
            ->addColumn('tgl_jatuh_tempo', function ($record) {
                return $record->show_tgl_jatuh_tempo;
            })
            ->addColumn('nominal', function ($record) {
                return number_format($record->aktiva->getTotalHarga(), 0, ',', '.');
            })
             ->addColumn('id_perpanjangan', function ($record) use ($user) {
                return $record->perpanjanganUmp ? $record->perpanjanganUmp->id_perpanjangan : '';
            })
            ->addColumn('status', function ($record) use ($user) {
                return $record->perpanjanganUmp ? $record->perpanjanganUmp->status : '';
            })
            ->addColumn('updated_by', function ($record) {
                if($record->perpanjanganUmp){
                    return $record->perpanjanganUmp->createdByRaw();
                }
                return '';
            })
            ->addColumn('action', function ($record) use ($user) {
                $actions = [];

                if($record->checkAction('extend', $this->perms)){
                    $actions[] = [
                        "id" => $record->id,
                        "page" => true,
                        "icon" => "fa fa-plus text-success",
                        "label" => "Ajukan Perpanjangan",
                        "url" => route($this->prepared("routes").".extend", $record)
                    ];
                }

                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(['asset', 'action','updated_by','status'])
            ->make(true);
    }

    public function extend(PjUmp $record)
    {
        $pic = $record->pengajuanUmp->picStaff;
        $kepala_dept = $record->pengajuanUmp->picKepala;
        $rekening =  RekeningBank::where('user_id', $pic->id)->first();
        $page_action = 'create';
        $pj = $record;
        $record = new PerpanjanganUmp();
        return $this->render($this->views.'.create', compact('rekening', 'record', 'pj', 'page_action', 'pic', 'kepala_dept'));
    }

    public function store(PerpanjanganUmpRequest $request)
    {
        $record = new PerpanjanganUmp();
        return $record->handleStoreOrUpdate($request);
    }

    public function edit(PerpanjanganUmp $record)
    {
        return $this->render($this->views.'.edit', compact('record'));
    }

    public function update(PerpanjanganUmp $record, PerpanjanganUmpRequest $request)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function show(PerpanjanganUmp $record)
    {
        return $this->render($this->views.'.show', compact('record'));
    }

    public function approval(PerpanjanganUmp $record)
    {
        return $this->render($this->views.'.approval', compact('record'));
    }

    public function approve(PerpanjanganUmp $record, Request $request)
    {
        return $record->handleApprove($request);
    }

    public function cancel(PerpanjanganUmp $record, Request $request)
    {
        return $record->handleCancel($request);
    }

    public function revise(PerpanjanganUmp $record, Request $request)
    {
        return $record->handleRevise($request);
    }

    public function reject(PerpanjanganUmp $record, Request $request)
    {
        return $record->handleReject($request);
    }

    public function verification(PerpanjanganUmp $record)
    {
        $pic = $record->pengajuanUmp()->picStaff;
        $kepala_dept = $record->pengajuanUmp()->picKepala;
        $rekening =  RekeningBank::where('user_id', $pic->id)->first();
        $page_action = 'verification';
        $pj = $record->pjUmp;
        return $this->render($this->views.'.verification', compact('rekening', 'record', 'pj', 'page_action', 'pic', 'kepala_dept'));
    }

    public function verify(PerpanjanganUmp $record, Request $request)
    {
        return $record->handleVerify($request);
    }

    public function history(PerpanjanganUmp $record)
    {
        $this->prepare(['title' => 'History Aktivitas']);
        return $this->render('globals.history', compact('record'));
    }

    public function tracking(PerpanjanganUmp $record)
    {
        return $this->render('globals.tracking', compact('record'));
    }


    public function print(PerpanjanganUmp $record)
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
