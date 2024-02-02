<?php

namespace App\Http\Controllers\OperationalCost;

use App\Http\Controllers\Controller;
use App\Http\Requests\OperationalCost\OperationalCostDetailRequest;
use App\Http\Requests\OperationalCost\OperationalCostRequest;
use App\Http\Requests\OperationalCost\OperationalCostUpdateRequest;
use App\Models\OperationalCost\OperationalCost;
use App\Models\OperationalCost\OperationalCostDetail;
use App\Models\Master\Barang\Vendor;
use App\Models\Master\Org\OrgStruct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OperationalCostController extends Controller
{
    protected $module = 'operational-cost';
    protected $routes = 'operational-cost';
    protected $views  = 'operational-cost';
    protected $perms = 'operational-cost';
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
                'title' => 'Biaya Operasional',
                'breadcrumb' => [
                    'Biaya Operasional' => route($this->routes . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $user = auth()->user();
        $records = OperationalCost::grid()->filters()->dtGet();

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
                    return $record->code . "<br>" . Carbon::parse($record->date)->format('d/m/Y');
                }
            )
            ->addColumn(
                'struct',
                function ($record) {
                    return $record->getStructName();
                }
            )
            ->addColumn(
                'jumlah_biaya',
                function ($record) {
                    return $record->details ? number_format($record->details->count(), 0, ',', '.') : "-";
                }
            )
            ->addColumn(
                'skema_pembayaran',
                function ($record) {
                    return $record->getSkemaPembayaran();
                }
            )
            ->addColumn(
                'total_biaya',
                function ($record) {
                    if ($record->details) {
                        $sum = $record->details->sum('cost');
                        return number_format($sum, 0, ',', '.');
                    }
                    return '-';
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
                    // return 6;
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
                    $actions[] = 'type:show|id:' . $record->id . '|label:Detail|icon: fa fa-plus text-info|page:true|url:/operational-cost/' . $record->id . '/detail';
                }
                if ($record->checkAction('revision', $this->perms)) {
                    $actions[] = 'type:edit|id:' . $record->id . '|page:true|label:Revisi|url:/operational-cost/pengajuan/' . $record->id . '/edit';
                }
                if ($record->checkAction('approval', $this->perms)) {
                    $actions[] = 'type:authorization|id:' . $record->id . '|page:true';
                }
                if ($record->checkAction('delete', $this->perms)) {
                    $actions[] = [
                        'type' => 'delete',
                        'id' => $record->id,
                        'attrs' => 'data-confirm-text="' . __('Hapus Pengajuan Biaya Operasional') . ' ' . $record->code . '?"',
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
                    'jumlah_biaya',
                    'skema_pembayaran',
                    'total_biaya',
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
                    $this->makeColumn('name:pengajuan|label:Pengajuan|className:text-center'),
                    $this->makeColumn('name:struct|label:Unit Kerja|className:text-center'),
                    $this->makeColumn('name:skema_pembayaran|label:Pembayaran|className:text-center'),
                    $this->makeColumn('name:jumlah_biaya|label:Jumlah Biaya|className:text-center'),
                    $this->makeColumn('name:total_biaya|label:Total Biaya (Rp)|className:text-right'),
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
        $data = $this->returnDataForm();
        $struct = auth()->user()->position->location ?? null;
        return $this->render($this->views . '.create', compact('data', 'struct'));
    }

    public function store(OperationalCostRequest $request)
    {
        $record = new OperationalCost;
        return $record->handleStoreOrUpdate($request);
    }

    public function edit(OperationalCost $record)
    {
        $struct = auth()->user()->position->location ?? null;
        return $this->render($this->views . '.edit', compact('record', 'struct'));
    }

    public function updateSummary($id, OperationalCostRequest $request)
    {
        $record = OperationalCost::find($id);
        return $record->handleStoreOrUpdate($request);
    }

    public function show(OperationalCost $record)
    {
        $data = $this->returnDataForm();
        $data['page_action'] = "show";
        $data['record'] = $record;
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_2' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:name|label:Nama Biaya|className:text-center'),
                        $this->makeColumn('name:total_biaya|label:Total Biaya|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action_show|label:Aksi|width:60px'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.detail', $data);
    }

    public function detail(OperationalCost $record)
    {
        $data = $this->returnDataForm();
        $data['record'] = $record;
        $data['page_action'] = "detail";
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_2' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:name|label:Nama Biaya|className:text-center'),
                        $this->makeColumn('name:total_biaya|label:Total Biaya|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.detail', $data);
    }

    public function detailGrid(OperationalCost $record)
    {
        $user = auth()->user();
        $records = OperationalCostDetail::with(['pengajuan'])
            ->whereHas(
                'pengajuan',
                function ($q) use ($record) {
                    $q->where('pengajuan_id', $record->id);
                }
            )->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC")
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($detail) {
                    return request()->start;
                }
            )
            ->addColumn(
                'name',
                function ($detail) {
                    return $detail->name ?? '';
                }
            )
            ->addColumn(
                'total_biaya',
                function ($detail) {
                    return number_format($detail->cost, 0, ",", ".") ?? '';
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
                    if ($detail->pengajuan->checkAction('edit', $this->perms)) {
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

    public function detailCreate(OperationalCost $record)
    {
        $vendors = Vendor::select("id", "name")->where('status', '=', '2')->get();
        $work_units = OrgStruct::select("id", "name")->get();
        $baseContentReplace = 'base-modal--render';

        return $this->render($this->views . '.detail.create', compact('record', 'baseContentReplace', 'work_units', 'vendors'));
    }

    public function detailStore(OperationalCostDetailRequest $request, OperationalCost $record)
    {
        $detail = new OperationalCostDetail;
        return $record->handleDetailStoreOrUpdate($request, $detail);
    }

    public function detailEdit(OperationalCostDetail $detail)
    {
        $record = $detail->pengajuan;
        $vendors = Vendor::select("id", "name")->where('status', '=', '2')->get();
        $work_units = OrgStruct::select("id", "name")->get();
        $baseContentReplace = 'base-modal--render';
        return $this->render($this->views . '.detail.edit', compact('record', 'detail', 'baseContentReplace', 'work_units', 'vendors'));
    }

    public function detailUpdate(OperationalCostDetailRequest $request, OperationalCostDetail $detail)
    {
        $record = $detail->pengajuan;
        return $record->handleDetailStoreOrUpdate($request, $detail);
    }

    public function detailShow(OperationalCostDetail $detail)
    {
        $record = $detail->pengajuan;
        $vendors = Vendor::select("id", "name")->where('status', '=', '2')->get();
        $work_units = OrgStruct::select("id", "name")->get();
        $baseContentReplace = 'base-modal--render';

        return $this->render($this->views . '.detail.show', compact('record', 'detail', 'baseContentReplace', 'work_units', 'vendors'));
    }

    public function detailDestroy(OperationalCostDetail $detail)
    {
        $record = $detail->pengajuan;
        return $record->handleDetailDestroy($detail);
    }

    public function submit(OperationalCost $record)
    {
        $this->prepare(['title' => 'Submit Data']);
        $flowApproval = $record->getFlowApproval($this->module);
        return $this->render($this->views . '.submit', compact('record', 'flowApproval'));
    }

    public function submitSave(OperationalCost $record, Request $request)
    {
        return $record->handleSubmitSave($request);
    }

    public function approval(OperationalCost $record)
    {
        $data = $this->returnDataForm();
        $data['record'] = $record;
        $data['page_action'] = "approval";
        $breadcrump = "Detil";
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route($this->routes . '.detailGrid', $record->id),
                    'datatable_2' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:name|label:Nama Biaya|className:text-center'),
                        $this->makeColumn('name:total_biaya|label:Total Biaya|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action_show|label:Aksi|width:60px'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.detail', $data);
    }

    public function approve(OperationalCost $record, Request $request)
    {
        return $record->handleApprove($request);
    }

    public function reject(OperationalCost $record, Request $request)
    {
        if ($request->action == "revision") {
            return $record->handleRevision($request);
        }
        return $record->handleReject($request);
    }

    public function update(OperationalCost $record, OperationalCostUpdateRequest $request)
    {
        return $record->handleSubmitDetail($request);
    }

    public function destroy(OperationalCost $record)
    {
        return $record->handleDestroy();
    }

    public function tracking(OperationalCost $record)
    {
        return $this->render('globals.tracking', compact('record'));
    }

    public function print(OperationalCost $record)
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

    public function history(OperationalCost $record)
    {
        return $this->render('globals.history', compact('record'));
    }

    private function returnDataForm()
    {
        $this->datas['vendors'] = Vendor::select("id", "name")->where('status', '=', '2')->get();
        $this->datas['work_units'] = OrgStruct::select("id", "name")->get();
        $this->datas['baseContentReplace'] = true;
        return $this->datas;
    }
}
