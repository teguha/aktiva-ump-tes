<?php

namespace App\Http\Controllers\Sgu;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sgu\DetailItemSguRequest;
use App\Http\Requests\Sgu\SguRequest;
use App\Models\Globals\Approval;
use App\Models\Master\Org\OrgStruct;
use App\Models\Sgu\PengajuanSgu;
use App\Models\Traits\Utilities;
use Carbon\Carbon;
// use App\Models\Master\SkemaPembayaran\SkemaPembayaran;
use Illuminate\Http\Request;

class SguController extends Controller
{
    //
    use Utilities;

    protected $module   = 'sgu';
    protected $routes   = 'sgu';
    protected $views    = 'sgu';
    protected $perms    = 'sgu';

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'Sewa Guna Usaha (SGU)',
                'breadcrumb' => [
                    'Sewa Guna Usaha (SGU)' => route($this->routes . '.index'),
                ]
            ]
        );
    }

    public function index()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:pengajuan_sgu|label:Pengajuan SGU|className:text-center'),
                        $this->makeColumn('name:work_unit|label:Unit Kerja|className:text-center'),
                        $this->makeColumn('name:rent_location|label:Lokasi Sewa|className:text-left'),
                        $this->makeColumn('name:pembayaran|label:Pembayaran|className:text-center'),
                        $this->makeColumn('name:rent_time_cost|label:Waktu & Harga|className:text-center'),
                        $this->makeColumn('name:status'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
                ],
            ]
        );

        return $this->render($this->views . '.index');
    }

    public function grid()
    {
        $user = auth()->user();
        $records = PengajuanSgu::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'pengajuan_sgu',
                function ($record) {
                    return $record->code . "<br>" . $record->submission_date->format('d/m/Y');
                }
            )
            ->addColumn('pembayaran', function ($record) {
                if ($record->payment_scheme == 'termin') {
                    return '<span class="badge badge-warning text-white">' . ucwords($record->payment_scheme) . '</span>';
                } else {
                    return '<span class="badge badge-primary text-white">' . strtoupper($record->payment_scheme) . '</span>';
                }
            })
            ->addColumn(
                'work_unit',
                function ($record) {
                    return $record->workUnit->name ?? '-';
                }
            )
            ->addColumn(
                'rent_time_cost',
                function ($record) {
                    return $record->rent_cost ? $record->rent_time_period . " Bulan <br>" . number_format($record->rent_cost, 0, ',', '.') : '-';
                }
            )
            ->addColumn('status', function ($record) {
                return $record->labelStatus($record->status ?? 'new');
            })
            ->addColumn('updated_by', function ($record) {
                return $record->createdByRaw();
            })
            ->addColumn('action', function ($record) use ($user) {
                if ($record->checkAction('show', $this->perms)) {
                    $actions[] = [
                        'type' => 'show',
                        'page' => true,
                        'id' => $record->id
                    ];
                }

                if ($record->checkAction('edit', $this->perms)) {
                    $actions[] = [
                        'type' => 'edit',
                        'id' => $record->id
                    ];
                }

                if ($record->checkAction('revision', $this->perms)) {
                    $actions[] = [
                        'type' => 'edit',
                        'label' => 'Revisi',
                        'page' => true,
                        'id' => $record->id,
                    ];
                }
                if ($record->checkAction('edit', $this->perms)) {
                    $actions[] = [
                        'type' => 'edit',
                        'label' => 'Detail',
                        'icon' => 'fa fa-plus text-info',
                        'page' => true,
                        'id' => $record->id,
                        'url' => route($this->routes . '.detail', $record->id)

                    ];
                }

                if ($record->checkAction('delete', $this->perms)) {
                    $actions[] = [
                        'type' => 'delete',
                        'id' => $record->id,
                        'attrs' => 'data-confirm-text="' . __('Hapus') . ' Pengajuan SGU ' . $record->code . '?"',
                    ];
                }
                if ($record->checkAction('approval', $this->perms)) {
                    $actions[] = [
                        'type' => 'approval',
                        'label' => 'Otorisasi',
                        'page' => 'true',
                        'id' => $record->id,
                        'url' => route($this->routes . '.approval', $record->id)
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
                    $actions[] = [
                        'type' => 'tracking',
                        'id' => $record->id,
                        'url' => route($this->routes . '.tracking', $record->id)
                    ];
                }

                if ($record->checkAction('history', $this->perms)) {
                    $actions[] = [
                        'type' => 'history',
                        'id' => $record->id,
                        'url' => route($this->routes . '.history', $record->id)
                    ];
                }

                return $this->makeButtonDropdown($actions);
            })
            ->rawColumns(['pengajuan_sgu', 'pembayaran', 'submission_date', 'work_unit', 'status', 'action', 'rent_time_cost', 'updated_by'])
            ->make(true);
    }

    public function create()
    {
        $record = new PengajuanSgu;
        return $this->render($this->views . '.create', compact('record'));
    }

    public function store(SguRequest $request)
    {

        $record = new PengajuanSgu;
        return $record->handleStoreOrUpdate($request);
    }

    public function edit(PengajuanSgu $record)
    {
        $baseContentReplace = false;
        return $this->render($this->views . '.edit', compact('record', 'baseContentReplace'));
    }

    public function updateSummary($id, SguRequest $request)
    {
        $record = PengajuanSgu::find($id);
        return $record->handleStoreOrUpdate($request);
    }

    public function detail(PengajuanSgu $record)
    {
        $breadcrump = "Detil";
        if ($record->status == "revision") {
            $breadcrump = "Revisi";
        }
        $this->prepare(
            [
                'breadcrumb' => [
                    'Sewa Guna Usaha (SGU)' => route($this->routes . '.index'),
                    $breadcrump => route($this->routes . '.detail', $record->id),
                ]
            ]
        );
        $startYear = date('Y');
        $page_action = 'edit';
        return $this->render($this->views . '.detail', compact('record', 'startYear', 'page_action'));
    }

    public function update(PengajuanSgu $record, DetailItemSguRequest $request)
    {
        return $record->handleSubmitDetail($request);
    }

    public function submit(PengajuanSgu $record)
    {
        $this->prepare(['title' => 'Submit Data']);
        $flowApproval = $record->getFlowApproval($this->module);
        return $this->render($this->views . '.submit', compact('record', 'flowApproval'));
    }

    public function submitSave(PengajuanSgu $record, Request $request)
    {
        return $record->handleSubmitSave($request);
    }

    public function destroy(PengajuanSgu $record)
    {
        return $record->handleDestroy();
    }

    public function show(PengajuanSgu $record)
    {
        $this->prepare(
            [
                'breadcrumb' => [
                    'Sewa Guna Usaha (SGU)' => route($this->routes . '.index'),
                    'Lihat' => route($this->routes . '.show', $record->id),
                ]
            ]
        );
        $page_action = "show";
        $tableData = [];
        if ($record->status != "draft") {
            $month = (int) Carbon::parse($record->depreciation_start_date)->month;
            $yearStart = Carbon::parse($record->depreciation_start_date)->year;
            $rent_time_period = ceil($record->rent_time_period / 12);
            $rent_time_period = $month > 1 ? $rent_time_period + 1 : $rent_time_period;

            for ($i = 0; $i < $rent_time_period; $i++) {
                $tableData[] = [
                    'no' => $i + 1,
                    'tahun' => $yearStart + $i,
                    'masa_manfaat' => $record->calculateMasaManfaat($i, $rent_time_period - 1),
                    'nilai_buku' => number_format($record->calculateNilaiBuku($i, $rent_time_period - 1), 0, ",", "."),
                    'depresiasi_per_bulan' => number_format($record->calculateDepresiasiPerBulan($i, $rent_time_period - 1), 0, ",", "."),
                    'depresiasi' => number_format($record->calculateDepresiasi($i, $rent_time_period - 1), 0, ",", "."),
                ];
            }
        }

        return $this->render($this->views . '.show', compact('record', 'page_action', 'tableData'));
    }

    public function history(PengajuanSgu $record)
    {
        $this->prepare(['title' => 'History Aktivitas']);
        return $this->render('globals.history', compact('record'));
    }

    public function updatePengajuanStatus(PengajuanSguRequest $request, PengajuanSgu $record)
    {
        $user = auth()->user();
        if ($record->status == 3) {
            PengajuanPembayaranSgu::insertBaseData($record->id);
            $record->handleSubmitSave($request);
        }
        return $record->handleStoreOrUpdate($request, $user, true);
    }

    public function approval(PengajuanSgu $record)
    {
        $this->prepare(
            [
                'breadcrumb' => [
                    'Sewa Guna Usaha (SGU)' => route($this->routes . '.index'),
                    'Otorisasi' => route($this->routes . '.approve', $record->id),
                ]
            ]
        );
        $page_action = "approval";
        $tableData = [];
        if ($record->status != "draft") {
            $month = (int) Carbon::parse($record->depreciation_start_date)->month;
            $yearStart = Carbon::parse($record->depreciation_start_date)->year;
            $rent_time_period = ceil($record->rent_time_period / 12);
            $rent_time_period = $month > 1 ? $rent_time_period + 1 : $rent_time_period;

            for ($i = 0; $i < $rent_time_period; $i++) {
                $tableData[] = [
                    'no' => $i + 1,
                    'tahun' => $yearStart + $i,
                    'masa_manfaat' => $record->calculateMasaManfaat($i, $rent_time_period - 1),
                    'nilai_buku' => number_format($record->calculateNilaiBuku($i, $rent_time_period - 1), 0, ",", "."),
                    'depresiasi_per_bulan' => number_format($record->calculateDepresiasiPerBulan($i, $rent_time_period - 1), 0, ",", "."),
                    'depresiasi' => number_format($record->calculateDepresiasi($i, $rent_time_period - 1), 0, ",", "."),
                ];
            }
        }
        return $this->render($this->views . '.approval', compact('record', 'page_action', 'tableData'));
    }

    public function approve(PengajuanSgu $record, Request $request)
    {
        return $record->handleApprove($request);
    }

    public function reject(PengajuanSgu $record, Request $request)
    {
        if ($request->action == "revision") {
            return $record->handleRevision($request);
        }
        return $record->handleReject($request);
    }

    public function tracking(PengajuanSgu $record)
    {
        return $this->render('globals.tracking', compact('record'));
    }

    public function print(PengajuanSgu $record)
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
