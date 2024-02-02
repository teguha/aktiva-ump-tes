<?php

namespace App\Http\Controllers\Termin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Termin\DetailTerminRequest;
use App\Http\Requests\Termin\TerminRequest;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Master\RekeningBank\Bank;
use App\Models\Master\RekeningBank\RekeningBank;
use App\Models\Termin\DetailTermin;
use App\Models\Termin\Termin;
use App\Support\Base;
use Illuminate\Http\Request;

class TerminPengajuanController extends Controller
{
    protected $module = 'termin.pengajuan';
    protected $routes = 'termin.pengajuan';
    protected $views = 'termin.pengajuan';
    protected $perms = 'termin.pengajuan';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Termin Pengajuan',
            'breadcrumb' => [
                'Home' => route('home'),
                'Termin' => route($this->routes . '.index'),
                'Termin Pengajuan' => route($this->routes . '.index'),
            ]
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:#|className:text-right'),
                    $this->makeColumn('name:pengajuan|label:PembelianAktiva / SGU|className:text-center'),
                    $this->makeColumn('name:struct|label:Unit Kerja|className:text-center'),
                    $this->makeColumn('name:jenis_pembayaran|label:Jenis Pembayaran|className:text-center'),
                    $this->makeColumn('name:nominal|label:Nominal (Rp)|className:text-right'),
                    $this->makeColumn('name:sisa_tagihan|label:Sisa Tagihan (Rp)|className:text-right'),
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
        $records = Termin::with('details')->grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn(
                '#',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'pengajuan',
                function ($record) {
                    if ($record->aktiva) {
                        return $record->aktiva->code . '<br>' . $record->aktiva->getTglPengajuanLabelAttribute();
                    } else {
                        return $record->pengajuanSgu->code . '<br>' . $record->pengajuanSgu->getTglPengajuanLabelAttribute();
                    }
                }
            )
            ->addColumn(
                'jenis_pembayaran',
                function ($record) {
                    return Base::makeLabel(ucwords('termin'), 'warning');
                }
            )
            ->addColumn(
                'nominal',
                function ($record) {
                    if ($record->aktiva) {
                        return number_format($record->aktiva->getTotalHarga(), 0, ',', '.');
                    } else {
                        return number_format($record->pengajuanSgu->rent_cost, 0, ',', '.');
                    }
                }
            )
            ->addColumn(
                'sisa_tagihan',
                function ($record) {
                    if ($record->aktiva) {
                        $sisa = $record->aktiva->getTotalHarga() - $record->details()->where('status', 'Terbayar')->sum('total');
                    } else {
                        $sisa = $record->pengajuanSgu->rent_cost - $record->details()->where('status', 'Terbayar')->sum('total');
                    }
                    return number_format($sisa, 0, ',', '.');
                }
            )
            ->addColumn(
                'struct',
                function ($record) {
                    return $record->struct ? $record->struct->name : '';
                }
            )
            ->addColumn(
                'status',
                function ($record) use ($user) {
                    return $record->labelStatus($record->status ?? 'new');
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) {
                    if ($record->status == Null || $record->status == 'new') {
                        return "";
                    } else {
                        return $record->createdByRaw();
                    }
                }
            )
            ->addColumn(
                'action',
                function ($record) use ($user) {
                    $actions = [];
                    if ($record->status == Null || $record->status == 'new') {
                        if ($record->checkAction('edit', $this->perms)) {
                            $actions[] = [
                                'type' => 'edit',
                                'label' => 'Tambah',
                                'icon' => 'fa fa-plus text-info',
                                'page' => true,
                                'id' => $record->id,
                                'url' => route($this->routes . '.detail', $record->id),
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
                                'url' => route($this->routes . '.detail', $record->id),
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
                }
            )
            ->rawColumns(['jenis_pembayaran', 'action', 'updated_by', 'status', 'pengajuan'])
            ->make(true);
    }

    public function detail(Termin $record)
    {
        $user = auth()->user();
        $page_action = 'edit';
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_2' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:termin|label:Termin|className:text-center'),
                        $this->makeColumn('name:nominal|label:Nominal|className:text-center'),
                        $this->makeColumn('name:pajak|label:Pajak|className:text-center'),
                        $this->makeColumn('name:total|label:Total|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );
        $this->pushBreadcrumb(['Detail' => route($this->routes . '.detail', $record)]);
        return $this->render($this->views . '.edit', compact('record', 'page_action'));
    }

    public function detailGrid(Termin $record)
    {
        $user = auth()->user();
        $records = DetailTermin::with(['termin'])
            ->whereHas(
                'termin',
                function ($q) use ($record) {
                    $q->where('termin_id', $record->id);
                }
            )->latest()
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($detail) {
                    return request()->start;
                }
            )
            ->addColumn(
                'termin',
                function ($detail) {
                    return $detail->no_termin ?? '';
                }
            )
            ->addColumn(
                'nominal',
                function ($detail) {
                    return number_format($detail->nominal, 0, ",", ".") ?? '';
                }
            )
            ->addColumn(
                'pajak',
                function ($detail) {
                    return number_format($detail->pajak, 0, ",", ".") ?? '';
                }
            )
            ->addColumn(
                'total',
                function ($detail) {
                    return number_format($detail->total, 0, ",", ".") ?? '';
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

                    if ($detail->termin->checkAction('edit', $this->perms)) {
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

    public function detailCreate(Termin $record)
    {
        return $this->render($this->views . '.detail.create', compact('record'));
    }

    public function detailStore(DetailTerminRequest $request, Termin $record)
    {
        $detail = new DetailTermin;
        return $record->handleDetailStoreOrUpdate($request, $detail);
    }

    public function detailEdit(DetailTermin $detail)
    {
        $record = $detail->termin;
        return $this->render($this->views . '.detail.edit', compact('record', 'detail'));
    }

    public function detailUpdate(DetailTerminRequest $request, DetailTermin $detail)
    {
        $record = $detail->termin;
        return $record->handleDetailStoreOrUpdate($request, $detail);
    }

    public function detailShow(DetailTermin $detail)
    {
        $record = $detail->termin;
        return $this->render($this->views . '.detail.show', compact('record', 'detail'));
    }

    public function detailDestroy(DetailTermin $detail)
    {
        $record = $detail->termin;
        return $record->handleDetailDestroy($detail);
    }

    public function update(TerminRequest $request, Termin $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function submit(Termin $record)
    {
        $this->prepare(['title' => 'Submit Data']);
        $flowApproval = $record->getFlowApproval($this->module);
        return $this->render($this->views . '.submit', compact('record', 'flowApproval'));
    }

    public function submitSave(Termin $record, Request $request)
    {
        return $record->handleSubmitSave($request);
    }

    public function approval(Termin $record)
    {
        $user = auth()->user();
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_2' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:termin|label:Termin|className:text-center'),
                        $this->makeColumn('name:nominal|label:Nominal|className:text-center'),
                        $this->makeColumn('name:pajak|label:Pajak|className:text-center'),
                        $this->makeColumn('name:total|label:Total|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action_show|label:Aksi'),
                    ]
                ]
            ]
        );
        $this->pushBreadcrumb(['Detail' => route($this->routes . '.approval', $record)]);
        return $this->render($this->views . '.approval', compact('record'));
    }

    public function approve(Termin $record, Request $request)
    {
        return $record->handleApprove($request);
    }

    public function tracking(Termin $record)
    {
        return $this->render('globals.tracking', compact('record'));
    }

    public function history(Termin $record)
    {
        $this->prepare(['title' => 'History Aktivitas']);
        return $this->render('globals.history', compact('record'));
    }

    public function show(Termin $record)
    {
        $user = auth()->user();
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_2' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:termin|label:Termin|className:text-center'),
                        $this->makeColumn('name:nominal|label:Nominal|className:text-center'),
                        $this->makeColumn('name:pajak|label:Pajak|className:text-center'),
                        $this->makeColumn('name:total|label:Total|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action_show|label:Aksi'),
                    ]
                ]
            ]
        );
        $this->pushBreadcrumb(['Detail' => route($this->routes . '.show', $record)]);
        return $this->render($this->views . '.show', compact('record'));
    }
}
