<?php

namespace App\Http\Controllers\Ump;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ump\DetailPenggunaanAnggaranRequest;
use App\Http\Requests\Ump\PjUmpRequest;
use App\Models\Master\RekeningBank\RekeningBank;
use App\Models\Ump\DetailPenggunaanAnggaran;
use App\Models\Ump\PjUmp;
use App\Support\Base;
use Carbon\Carbon;
use Illuminate\Http\Request;


class PjController extends Controller
{
    protected $module = 'ump.pj-ump';
    protected $routes = 'ump.pj-ump';
    protected $views = 'ump.pj';
    protected $perms = 'ump.pj-ump';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Pertanggungjawaban UMP',
            'breadcrumb' => [
                'Home' => route('home'),
                'UMP' => '#',
                'PJ UMP' => route($this->routes . '.index'),
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
                    $this->makeColumn('name:pj_ump|label:Pertanggungjawaban|className:text-center'),
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
        $records = PjUmp::with('pengajuanUmp', 'pengajuanUmp.pengajuanSgu', 'pengajuanUmp.aktiva')->grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('#', function ($record) {
                return request()->start;
            })
            ->addColumn('code', function ($record) {
                return $record->pengajuanUmp->code_ump . "<br>" . $record->pengajuanUmp->date_ump->format('d/m/Y');
            })
            ->addColumn('struct', function ($record) {
                return $record->pengajuanUmp->struct->name;
            })
            ->addColumn('nominal', function ($record) {
                return number_format($record->pengajuanUmp->nominal_pembayaran , 0, ',', '.');
            })
            ->addColumn('pj_ump', function ($record) {
                if ($record->id_pj_ump) {
                    return $record->id_pj_ump . "<br>" . $record->tgl_pj_ump->format('d/m/Y');
                }
                return "-";
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
            ->rawColumns(['action', 'updated_by', 'status', 'code', 'pj_ump'])
            ->make(true);
    }

    public function edit(PjUmp $record)
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:#|className:text-right'),
                    $this->makeColumn('name:penggunaan|label:Penggunaan|className:text-left'),
                    $this->makeColumn('name:mata_anggaran|label:Mata Anggaran|className:text-left'),
                    $this->makeColumn('name:nama_mata_anggaran|label:Nama Mata Anggaran|className:text-left'),
                    $this->makeColumn('name:nominal|label:Nominal (Rp)|className:text-left'),
                ],
                'url' => route($this->routes . '.detail', $record->id),
            ],
        ]);
        $this->pushBreadcrumb(['Detil' => route($this->routes . '.edit', $record)]);
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(PjUmpRequest $request, PjUmp $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function approval(PjUmp $record)
    {
        return $this->render($this->views . '.approval', compact('record'));
    }

    public function approve(PjUmp $record, Request $request)
    {
        return $record->handleApprove($request);
    }

    public function cancel(PjUmp $record, Request $request)
    {
        return $record->handleCancel($request);
    }

    public function revise(PjUmp $record, Request $request)
    {
        return $record->handleRevise($request);
    }

    public function history(PjUmp $record)
    {
        $this->prepare(['title' => 'History Aktivitas']);
        return $this->render('globals.history', compact('record'));
    }

    public function verification(PjUmp $record)
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:#|className:text-right'),
                    $this->makeColumn('name:penggunaan|label:Penggunaan|className:text-left'),
                    $this->makeColumn('name:mata_anggaran|label:Mata Anggaran|className:text-left'),
                    $this->makeColumn('name:nama_mata_anggaran|label:Nama Mata Anggaran|className:text-left'),
                    $this->makeColumn('name:nominal|label:Nominal (Rp)|className:text-left'),
                ],
                'url' => route($this->routes . '.detail', $record->id),
            ],
        ]);
        $urlAdd = route($this->routes . '.detail.add', $record->id);
        if ($record->status != 'waiting verification') {
            return redirect()->route($this->routes . '.show', $record);
        }
        $pengajuan = $record->pengajuanUmp;
        $pic = $pengajuan->picStaff;
        $kepala_dept = $pengajuan->picKepala;
        $rekening =  RekeningBank::where('user_id', $pic->id)->first();
        $page_action = 'verification';
        $this->pushBreadcrumb(['Verifikasi' => route($this->routes . '.verification', $record)]);
        return $this->render($this->views . '.verification', compact('rekening', 'record', 'page_action', 'pic', 'kepala_dept', 'pengajuan', 'urlAdd'));
    }

    public function verify(PjUmp $record, Request $request)
    {
        return $record->handleVerify($request);
    }

    public function transfer(PjUmp $record)
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:#|className:text-right'),
                    $this->makeColumn('name:penggunaan|label:Penggunaan|className:text-left'),
                    $this->makeColumn('name:mata_anggaran|label:Mata Anggaran|className:text-left'),
                    $this->makeColumn('name:nama_mata_anggaran|label:Nama Mata Anggaran|className:text-left'),
                    $this->makeColumn('name:nominal|label:Nominal (Rp)|className:text-left'),
                ],
                'url' => route($this->routes . '.detail', $record->id),
            ],
            
            'jurnalTableStruct' => [
                'datatable_2' => [
                    $this->makeColumn('name:#|className:text-right'),
                    $this->makeColumn('name:mata_anggaran|label:Mata Anggaran|className:text-left'),
                    $this->makeColumn('name:nama_mata_anggaran|label:Nama Mata Anggaran|className:text-left'),
                    $this->makeColumn('name:debit|label:Debit (Rp)|className:text-left'),
                    $this->makeColumn('name:kredit|label:Kredit (Rp)|className:text-left'),
                ],
                'url' => route($this->routes . '.detail.jurnal', $record->id),
            ],
        ]);
        $penggunaan_total = $record->detailPenggunaanAnggaran->sum('nominal');
        if ($record->status != 'pay remaining') {
            return redirect()->route($this->routes . '.show', $record);
        }
        $pengajuan = $record->pengajuanUmp;
        $pic = $pengajuan->picStaff;
        $kepala_dept = $pengajuan->picKepala;
        $rekening =  RekeningBank::where('user_id', $pic->id)->first();
        $page_action = 'transfer';
        $this->pushBreadcrumb(['Transfer' => route($this->routes . '.transfer', $record)]);
        return $this->render($this->views . '.transfer', compact('rekening', 'record', 'page_action', 'pic', 'kepala_dept', 'pengajuan'));
    }

    public function pay(PjUmp $record, Request $request)
    {
        return $record->handlePayment($request);
    }

    public function addDetail($id_pj_ump)
    {
        $title = "Detil Penggunaan Anggaran";
        return $this->render($this->views . '.detail', compact('id_pj_ump', 'title'));
    }

    public function submitDetail(DetailPenggunaanAnggaranRequest $request, $id_pj_ump)
    {
        $record = new DetailPenggunaanAnggaran;
        $record->id_pj_ump = $id_pj_ump;
        return $record->handleStoreOrUpdate($request);
    }

    public function detailJurnalGrid($id)
    {
        $user = auth()->user();
        $pj_ump = PjUmp::where('id', $id)->first();
        $records = $pj_ump->getDetailJurnal();

        return \DataTables::of($records)
            ->addColumn('#', function ($record) {
                return request()->start;
            })
            ->addColumn('mata_anggaran', function ($record) {
                return $record["mata_anggaran"];
            })
            ->addColumn('nama_mata_anggaran', function ($record) {
                return $record["nama_mata_anggaran"];
            })
            ->addColumn('debit', function ($record) {
                return $record["debit"];
            })
            ->addColumn('kredit', function ($record) {
                return $record["kredit"];
            })
            ->rawColumns(['mata_anggaran', 'nama_mata_anggaran', 'debit', 'kredit'])
            ->make(true);
    }

    public function detailPenggunaanAnggaran($id)
    {
        $user = auth()->user();
        $records = DetailPenggunaanAnggaran::where('id_pj_ump', $id);
        return \DataTables::of($records)
            ->addColumn('#', function ($record) {
                return request()->start;
            })
            ->addColumn('penggunaan', function ($record) {
                return $record->penggunaan;
            })
            ->addColumn('mata_anggaran', function ($record) {
                return $record->mataAnggaran->mata_anggaran;
            })
            ->addColumn('nama_mata_anggaran', function ($record) {
                return $record->mataAnggaran->nama;
            })
            ->addColumn('nominal', function ($record) {
                return number_format($record->nominal, 0, ",", ".");
            })
            ->rawColumns(['penggunaan', 'mata_anggaran', 'nama_mata_anggaran', 'nominal', 'hidden_column'])
            ->make(true);
    }

    public function show(PjUmp $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function tracking(PjUmp $record)
    {
        return $this->render('globals.tracking', compact('record'));
    }


    // ------------------------------------------------- //
    // Catak Print "Pengajuan"
    // ------------------------------------------------- //
    public function print(PjUmp $record)
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
