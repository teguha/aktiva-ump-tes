<?php

namespace App\Http\Controllers\Ump;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ump\PengajuanUmpRequest;
use App\Models\Master\RekeningBank\RekeningBank;
use App\Models\Ump\PengajuanUmp;
use App\Support\Base;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    protected $module = 'ump.pengajuan-ump';
    protected $routes = 'ump.pengajuan-ump';
    protected $views = 'ump.pengajuan';
    protected $perms = 'ump.pengajuan-ump';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Pengajuan UMP',
            'breadcrumb' => [
                'Home' => route('home'),
                'UMP' => '#',
                'Pengajuan UMP' => route($this->routes . '.index'),
            ]
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:#|className:text-right'),
                    $this->makeColumn('name:struct|label:Unit Kerja|className:text-center'),
                    $this->makeColumn('name:nominal|label:Nominal (Rp)|className:text-center'),
                    $this->makeColumn('name:pengajuan_ump|label:Pengajuan UMP|className:text-center'),
                    $this->makeColumn('name:perihal|label:Perihal|className:text-center'),
                    $this->makeColumn('name:status'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);
        return $this->render($this->views . '.index');
    }

    public function grid()
    {
        $user = auth()->user();
        $records = PengajuanUmp::with('rekening', 'rekening.owner')->grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('#', function ($record) {
                return request()->start;
            })
            ->addColumn('pengajuan', function ($record) {
                if ($record->aktiva) {
                    return $record->aktiva->code . "<br>" . $record->aktiva->getTglPengajuanLabelAttribute();
                } elseif ($record->pengajuanSgu) {
                    return $record->pengajuanSgu->code . "<br>" . $record->pengajuanSgu->submission_date->format('d/m/Y');
                } else {
                    return '-';
                }
            })
            ->addColumn('perihal', function ($record) {
                if ($record->aktiva) {
                    return ucwords($record->aktiva->itemName());
                } else if ($record->pengajuanSgu) {
                    return ucwords($record->pengajuanSgu->rent_location);
                } else {
                    return ucwords($record->perihal);
                }
            })
            ->addColumn('nominal', function ($record) {
                return number_format($record->nominal_pembayaran, 0, ',', '.');

            })
            ->addColumn('struct', function ($record) {
                return $record->struct ? $record->struct->name : '';
            })
            ->addColumn('pengajuan_ump', function ($record) {
                if ($record->code_ump) {
                    return $record->code_ump . "<br>" . $record->date_ump->format('d/m/Y');
                }
                return '';
            })
            ->addColumn('perihal', function ($record) {
                if ($record->perihal) {
                    return str_word_count($record->perihal) . " Words" . "<br>" . $record->files()->count() . " Files";
                }
                return '';
            })
            ->addColumn('status', function ($record) use ($user) {
                return $record->labelStatus($record->status ?? 'new');
            })
            ->addColumn('updated_by', function ($record) {
                if ($record->status == Null || $record->status == 'new') {
                    return "";
                } else {
                    return $record->createdByRaw();
                }
            })
            ->addColumn('action', function ($record) use ($user) {
                $actions = [];
                if ($record->status == Null || $record->status == 'new') {
                    if ($record->checkAction('edit', $this->perms)) {
                        $actions[] = [
                            'type' => 'edit',
                            'label' => 'Tambah',
                            'icon' => 'fa fa-plus text-info',
                            'page' => true,
                            'id' => $record->id,
                            'url' => route($this->routes . '.edit', $record->id),
                        ];
                    }
                } else {
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
                    if ($record->checkAction('payment', $this->perms)) {
                        $actions[] = [
                            'page' => true,
                            'icon' => 'fa fa-edit text-success',
                            'label' => 'Bayar',
                            'url' => route($this->prepared('routes') . '.payment', $record)
                        ];
                    }
                    if ($record->checkAction('verification', $this->perms)) {
                        $actions[] = [
                            'page' => true,
                            'icon' => 'fa fa-edit text-success',
                            'label' => 'Verifikasi',
                            'url' => route($this->prepared('routes') . '.verification', $record)
                        ];
                    }
                    if ($record->checkAction('confirmation', $this->perms)) {
                        $actions[] = [
                            'page' => true,
                            'icon' => 'fa fa-edit text-success',
                            'label' => 'Konfirmasi',
                            'url' => route($this->prepared('routes') . '.confirmation', $record)
                        ];
                    }
                    if ($record->checkAction('revision', $this->perms)) {
                        $actions[] = [
                            'page' => true,
                            'icon' => 'fa fa-edit text-success',
                            'label' => 'Revisi',
                            'url' => route($this->prepared('routes') . '.edit', $record)
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
            ->rawColumns(['pengajuan', 'jenis_pembayaran', 'kategori', 'action', 'updated_by', 'status', 'pengajuan_ump', 'perihal'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(PengajuanUmpRequest $request)
    {
        $record = new PengajuanUmp;
        return $record->handleStoreOrUpdate($request);
    }


    public function edit(PengajuanUmp $record)
    {
        $this->pushBreadcrumb(['Detil' => route($this->routes . '.edit', $record)]);
        return $this->render($this->views . '.edit', compact('record'));
    }


    public function update(PengajuanUmpRequest $request, PengajuanUmp $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function approval(PengajuanUmp $record)
    {
        return $this->render($this->views . '.approval', compact('record'));
    }

    public function approve(PengajuanUmp $record, Request $request)
    {
        return $record->handleApprove($request);
    }

    public function reject(PengajuanUmp $record, Request $request)
    {
        if ($request->action == "revision") {
            return $record->handleRevision($request);
        }
        return $record->handleReject($request);
    }

    public function cancel(PengajuanUmp $record, Request $request)
    {
        return $record->handleCancel($request);
    }

    public function revise(PengajuanUmp $record, Request $request)
    {
        return $record->handleRevise($request);
    }

    public function history(PengajuanUmp $record)
    {
        $this->prepare(['title' => 'History Aktivitas']);
        return $this->render('globals.history', compact('record'));
    }

    public function verification(PengajuanUmp $record)
    {
        if ($record->status != 'waiting verification') {
            return redirect()->route($this->routes . '.show', $record);
        }
        $pic = $record->picStaff;
        $kepala_dept = $record->picKepala;
        $rekening =  RekeningBank::where('user_id', $pic->id)->first();
        $page_action = 'verification';
        $this->pushBreadcrumb(['Verifikasi' => route($this->routes . '.verification', $record)]);
        return $this->render($this->views . '.verification', compact('rekening', 'record', 'page_action', 'pic', 'kepala_dept'));
    }

    public function verify(PengajuanUmp $record, Request $request)
    {
        return $record->handleVerify($request);
    }

    public function payment(PengajuanUmp $record)
    {
        if ($record->status != 'waiting payment') {
            return redirect()->route($this->routes . '.show', $record);
        }
        $pic = $record->picStaff;
        $kepala_dept = $record->picKepala;
        $rekening =  RekeningBank::where('user_id', $pic->id)->first();
        $page_action = 'payment';
        $this->pushBreadcrumb(['Bayar' => route($this->routes . '.payment', $record)]);
        return $this->render($this->views . '.payment', compact('rekening', 'record', 'page_action', 'pic', 'kepala_dept'));
    }

    public function pay(PengajuanUmp $record, Request $request)
    {
        return $record->handlePayment($request);
    }

    public function confirmation(PengajuanUmp $record)
    {
        if ($record->status != 'waiting confirmation') {
            return redirect()->route($this->routes . '.show', $record);
        }
        $pic = $record->picStaff;
        $kepala_dept = $record->picKepala;
        $rekening =  RekeningBank::where('user_id', $pic->id)->first();
        $page_action = 'confirmation';
        $this->pushBreadcrumb(['Konfirmasi' => route($this->routes . '.confirmation', $record)]);
        return $this->render($this->views . '.confirmation', compact('rekening', 'record', 'page_action', 'pic', 'kepala_dept'));
    }

    public function confirm(PengajuanUmp $record, Request $request)
    {
        return $record->handleConfirmation($request);
    }

    public function show(PengajuanUmp $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function tracking(PengajuanUmp $record)
    {
        return $this->render('globals.tracking', compact('record'));
    }

    public function print(PengajuanUmp $record)
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
