<?php

namespace App\Http\Controllers\UMP;

use App\Http\Controllers\Controller;
use App\Http\Requests\UMP\PembayaranUMPRequest;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Master\RekeningBank\Bank;
use App\Models\Master\RekeningBank\RekeningBank;
use App\Models\UMP\PembayaranUMP;
use App\Models\UMP\PjUMP;
use App\Support\Base;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    protected $module = 'ump.pembayaran-ump';
    protected $routes = 'ump.pembayaran-ump';
    protected $views = 'ump.pembayaran';
    protected $perms = 'ump.pembayaran-ump';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms.'.view',
            'title' => 'Pembayaran UMP',
            'breadcrumb' => [
                'Home' => route('home'),
                'UMP' => '#',
                'Pembayaran UMP' => route($this->routes.'.index'),
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
                    $this->makeColumn('name:pembayaran|label:Pembayaran|className:text-center'),
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
        $records = PembayaranUMP::with('pengajuanUmp', 'pengajuanUmp.pengajuanSgu', 'pengajuanUmp.aktiva')->grid()->filters()->dtGet();

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
            ->addColumn('pembayaran', function ($record) {
                if($record->id_ump_pembayaran){
                    return $record->id_ump_pembayaran . "<br>" . $record->tgl_ump_pembayaran->format('d/m/Y');
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
            ->rawColumns(['action','updated_by','status', 'code', 'id_pengajuan', 'pembayaran'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views.'.modal.create');
    }

    public function store(PembayaranUMPRequest $request)
    {
        $record = new PembayaranUMP();
        return $record->handleStoreOrUpdate($request);
    }

    public function edit(PembayaranUMP $record)
    {
        return $this->render($this->views.'.edit', compact('record'));
    }

    public function update(PembayaranUMP $record, PembayaranUMPRequest $request)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function show(PembayaranUMP $record)
    {
        return $this->render($this->views.'.show', compact('record'));
    }

    public function approval(PembayaranUMP $record)
    {
        return $this->render($this->views.'.approval', compact('record'));
    }

    public function approve(PembayaranUMP $record, Request $request)
    {
        return $record->handleApprove($request);
    }

    public function cancel(PembayaranUMP $record, Request $request)
    {
        return $record->handleCancel($request);
    }

    public function revise(PembayaranUMP $record, Request $request)
    {
        return $record->handleRevise($request);
    }

    public function reject(PembayaranUMP $record, Request $request)
    {
        return $record->handleReject($request);
    }

    public function history(PembayaranUMP $record)
    {
        $this->prepare(['title' => 'History Aktivitas']);
        return $this->render('globals.history', compact('record'));
    }

    public function tracking(PembayaranUmp $record)
    {
        return $this->render('globals.tracking', compact('record'));
    }


    // ------------------------------------------------- //
    // Catak Print "Pengajuan"
    // ------------------------------------------------- //
    public function print(PembayaranUMP $record)
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
