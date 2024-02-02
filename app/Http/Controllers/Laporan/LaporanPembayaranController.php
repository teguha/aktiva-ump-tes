<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Ump\PengajuanUmp;
use App\Models\Ump\PerpanjanganUmp;
use App\Models\UMP\PembayaranUMP;
use App\Models\Ump\PjUmp;
use App\Models\Termin\Termin;
use App\Models\Termin\TerminPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class LaporanPembayaranController extends Controller
{
    protected $module   = 'laporan.laporan-pembayaran';
    protected $routes   = 'laporan.laporan-pembayaran';
    protected $views    = 'laporan.laporan-pembayaran';
    protected $perms    = 'laporan';

    const TYPE = [
        'ump-pengajuan'           => [
            'route' => 'ump.pengajuan-ump',
            'scope' => '',
            'show'  => 'UMP Pengajuan',
        ],
        'ump-perpanjangan'       => [
            'route' => 'ump.perpanjangan-ump',
            'scope' => '',
            'show'  => 'UMP Perpanjangan',
        ],
        'ump-pembayaran'       => [
            'route' => 'ump.pembayaran-ump',
            'scope' => '',
            'show'  => 'UMP Pembayaran',
        ],
        'ump-pertanggungjawaban'           => [
            'route' => 'ump.pj-ump',
            'scope' => '',
            'show'  => 'UMP Pertanggungjawaban',
        ],
        'termin-pengajuan'       => [
            'route' => 'termin.pengajuan',
            'scope' => '',
            'show'  => 'Termin Pengajuan',
        ],
        'termin-pembayaran'       => [
            'route' => 'termin.pembayaran',
            'scope' => '',
            'show'  => 'Termin Pembayaran',
        ],
    ];

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'Laporan Pembayaran',
                'breadcrumb' => [
                    'Laporan' => route($this->routes . '.index'),
                    'Laporan Pembayaran' => route($this->routes . '.index'),
                ],
            ]
        );
    }

    public function index()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:#|className:text-right'),
                        $this->makeColumn('name:pengajuan|label:Pengajuan Aktiva/SGU|className:text-center'),
                        $this->makeColumn('name:struct|label:Unit Kerja|className:text-center'),
                        $this->makeColumn('name:nominal|label:Nominal (Rp)|className:text-center'),
                        $this->makeColumn('name:pengajuan_ump|label:Pengajuan UMP|className:text-center'),
                        $this->makeColumn('name:jatuh_tempo|label:Tgl Jatuh Tempo|className:text-center'),
                        $this->makeColumn('name:perihal|label:Perihal|className:text-center'),
                        $this->makeColumn('name:status'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
                ]
            ]
        ); 
    
        

        return $this->render(
            $this->views . '.index',
            [
                'TYPE'  => Self::TYPE,
            ]
        );
    }

    public function indexUmpPengajuan()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route('laporan.laporan-pembayaran.gridUmpPengajuan'),
                    'datatable_1' => [
                        $this->makeColumn('name:#|className:text-right'),
                        $this->makeColumn('name:pengajuan|label:Pengajuan Aktiva/SGU|className:text-center'),
                        $this->makeColumn('name:struct|label:Unit Kerja|className:text-center'),
                        $this->makeColumn('name:nominal|label:Nominal (Rp)|className:text-center'),
                        $this->makeColumn('name:pengajuan_ump|label:Pengajuan UMP|className:text-center'),
                        $this->makeColumn('name:jatuh_tempo|label:Tgl Jatuh Tempo|className:text-center'),
                        $this->makeColumn('name:perihal|label:Perihal|className:text-center'),
                        $this->makeColumn('name:status'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
                ]
            ]
        ); 

        return $this->render(
            $this->views . '.index',
            [
                'TYPE'  => Self::TYPE,
            ]
        );
    }

    public function indexUmpPerpanjangan()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route('laporan.laporan-pembayaran.gridUmpPerpanjangan'),
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
                ]
            ]
        ); 

        

        return $this->render(
            $this->views . '.index',
            [
                'TYPE'  => Self::TYPE,
            ]
        );
    }

    public function indexUmpPembayaran()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route('laporan.laporan-pembayaran.gridUmpPembayaran'),
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
                ]
            ]
        ); 

        return $this->render(
            $this->views . '.index',
            [
                'TYPE'  => Self::TYPE,
            ]
        );
    }

    public function indexUmpPertanggungjawaban()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route('laporan.laporan-pembayaran.gridUmpPertanggungjawaban'),
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
                ]
            ]
        ); 

        return $this->render(
            $this->views . '.index',
            [
                'TYPE'  => Self::TYPE,
            ]
        );
    }

    public function indexTerminPengajuan()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route('laporan.laporan-pembayaran.gridTerminPengajuan'),
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
                ]
            ]
        ); 

        return $this->render(
            $this->views . '.index',
            [
                'TYPE'  => Self::TYPE,
            ]
        );
    }

    public function indexTerminPembayaran()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url' => route('laporan.laporan-pembayaran.gridTerminPembayaran'),
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
                ]
            ]
        ); 

        return $this->render(
            $this->views . '.index',
            [
                'TYPE'  => Self::TYPE,
            ]
        );
    }


    public function grid(Request $request)
    {
        $user = auth()->user();
        $records = [];
        $type       = Self::TYPE[$request->type] ?? [];
        $route      = $type['route'] ?? '';
        $scope      = $type['scope'] ?? '';
        $show       = $type['show'] ?? '';
        if (in_array($request->type, ['ump-pengajuan', 'ump-perpanjangan', 'ump-pembayaran', 'ump-pertanggungjawaban', 'termin-pengajuan', 'termin-pembayaran']) || ($request->year || $request->category || $request->object || $request->schedule)) {
            $this->prepare(
                [
                    'routes' => $route,
                ]
            );
            $user = auth()->user();

            $records = PengajuanUmp::with('rekening', 'rekening.owner')->grid()->filters()->dtGet();
            
        }
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
                if ($record->aktiva) {
                    return number_format($record->aktiva->getTotalHarga(), 0, ',', '.');
                } elseif ($record->pengajuanSgu) {
                    return number_format($record->pengajuanSgu->rent_cost, 0, ',', '.');
                } else {
                    return '';
                }
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
            ->addColumn('jatuh_tempo', function ($record) {
                if($record->tgl_jatuh_tempo){
                    return $record->tgl_jatuh_tempo->format('d/m/Y') ;
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
                $actions[] = [
                    'type' => 'print',
                    'page' => 'true',
                    'id' => $record->id,
                    'url' => route($this->routes . '.print', $record->id)
                ];
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(['pengajuan', 'jenis_pembayaran', 'kategori', 'action', 'updated_by', 'status', 'pengajuan_ump', 'perihal'])
            ->make(true);
    }

    public function gridUmpPengajuan(Request $request)
    {
        $user = auth()->user();
        $records = [];
        $type       = Self::TYPE['ump-pengajuan'] ?? [];
        $route      = $type['route'] ?? '';
        $scope      = $type['scope'] ?? '';
        $show       = $type['show'] ?? '';
        $this->prepare(
            [
                'routes' => $route,
            ]
        );
        $records = PengajuanUmp::with('rekening', 'rekening.owner')->gridStatusCompleted()->filters()->dtGet();

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
                if ($record->aktiva) {
                    return number_format($record->aktiva->getTotalHarga(), 0, ',', '.');
                } elseif ($record->pengajuanSgu) {
                    return number_format($record->pengajuanSgu->rent_cost, 0, ',', '.');
                } else {
                    return '';
                }
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
            ->addColumn('jatuh_tempo', function ($record) {
                if($record->tgl_jatuh_tempo){
                    return $record->tgl_jatuh_tempo->format('d/m/Y') ;
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
            ->addColumn('action', function ($record) use ($user, $route) {
                $actions = [];
                $actions[] = [
                    'type' => 'print',
                    'page' => 'true',
                    'id' => $record->id,
                    'url' => route($route . '.print', $record->id)
                ];
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(['pengajuan', 'jenis_pembayaran', 'kategori', 'action', 'updated_by', 'status', 'pengajuan_ump', 'perihal'])
            ->make(true);
    }

    public function gridUmpPerpanjangan(Request $request)
    {
        $user = auth()->user();
        $records = [];
        $type       = Self::TYPE['ump-perpanjangan'] ?? [];
        $route      = $type['route'] ?? '';
        $scope      = $type['scope'] ?? '';
        $show       = $type['show'] ?? '';
        $this->prepare(
            [
                'routes' => $route,
            ]
        );
        $records = PerpanjanganUmp::with('pengajuanUmp', 'pengajuanUmp.pengajuanSgu', 'pengajuanUmp.aktiva')->gridStatusCompleted()->filters()->dtGet();

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
                if($record->pengajuanUmp->aktiva){
                    return number_format($record->pengajuanUmp->aktiva->getTotalHarga(), 0, ',', '.');
                }elseif($record->pengajuanUmp->pengajuanSgu){
                    return number_format($record->pengajuanUmp->pengajuanSgu->rent_cost, 0, ',', '.');
                }else{
                    return '';
                }
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
            ->addColumn('action', function ($record) use ($user, $route) {
                $actions = [];
                $actions[] = [
                    'type' => 'print',
                    'page' => 'true',
                    'id' => $record->id,
                    'url' => route($route . '.print', $record->id)
                ];
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(['action','updated_by','status', 'code', 'perpanjangan'])
            ->make(true);
    }

    public function gridUmpPembayaran(Request $request)
    {
        $user = auth()->user();
        $records = [];
        $type       = Self::TYPE['ump-pembayaran'] ?? [];
        $route      = $type['route'] ?? '';
        $scope      = $type['scope'] ?? '';
        $show       = $type['show'] ?? '';
        $this->prepare(
            [
                'routes' => $route,
            ]
        );
        $records = PembayaranUMP::with('pengajuanUmp', 'pengajuanUmp.pengajuanSgu', 'pengajuanUmp.aktiva')->gridStatusCompleted()->filters()->dtGet();

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
                if($record->pengajuanUmp->aktiva){
                    return number_format($record->pengajuanUmp->aktiva->getTotalHarga(), 0, ',', '.');
                }elseif($record->pengajuanUmp->pengajuanSgu){
                    return number_format($record->pengajuanUmp->pengajuanSgu->rent_cost, 0, ',', '.');
                }else{
                    return '';
                }
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
            ->addColumn('action', function ($record) use ($user, $route) {
                $actions = [];
                $actions[] = [
                    'type' => 'print',
                    'page' => 'true',
                    'id' => $record->id,
                    'url' => route($route . '.print', $record->id)
                ];
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(['action','updated_by','status', 'code', 'id_pengajuan', 'pembayaran'])
            ->make(true);
    }

    public function gridUmpPertanggungjawaban(Request $request)
    {
        $user = auth()->user();
        $records = [];
        $type       = Self::TYPE['ump-pertanggungjawaban'] ?? [];
        $route      = $type['route'] ?? '';
        $scope      = $type['scope'] ?? '';
        $show       = $type['show'] ?? '';
        $this->prepare(
            [
                'routes' => $route,
            ]
        );
        $records = PjUmp::with('pengajuanUmp', 'pengajuanUmp.pengajuanSgu', 'pengajuanUmp.aktiva')->gridStatusCompleted()->filters()->dtGet();

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
                if ($record->pengajuanUmp->aktiva) {
                    return number_format($record->pengajuanUmp->aktiva->getTotalHarga(), 0, ',', '.');
                } else {
                    return number_format($record->pengajuanUmp->pengajuanSgu->rent_cost, 0, ',', '.');
                }
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
            ->addColumn('action', function ($record) use ($user, $route) {
                $actions = [];
                $actions[] = [
                    'type' => 'print',
                    'page' => 'true',
                    'id' => $record->id,
                    'url' => route($route . '.print', $record->id)
                ];
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(['action', 'updated_by', 'status', 'code', 'pj_ump'])
            ->make(true);
    }

    public function gridTerminPengajuan(Request $request)
    {
        $user = auth()->user();
        $records = [];
        $type       = Self::TYPE['termin-pengajuan'] ?? [];
        $route      = $type['route'] ?? '';
        $scope      = $type['scope'] ?? '';
        $show       = $type['show'] ?? '';
        $this->prepare(
            [
                'routes' => $route,
            ]
        );
        $records = Termin::with('details')->gridStatusCompleted()->filters()->dtGet();

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
                function ($record) use ($user, $route) {
                    $actions = [];
                    $actions[] = [
                        'type' => 'print',
                        'page' => 'true',
                        'id' => $record->id,
                        'url' => route($route . '.print', $record->id)
                    ];
                    return $this->makeButtonDropdown($actions, $record->id);
                }
            )
            ->rawColumns(['jenis_pembayaran', 'action', 'updated_by', 'status', 'pengajuan'])
            ->make(true);
    }

    public function gridTerminPembayaran(Request $request)
    {
        $user = auth()->user();
        $records = [];
        $type       = Self::TYPE['termin-pembayaran'] ?? [];
        $route      = $type['route'] ?? '';
        $scope      = $type['scope'] ?? '';
        $show       = $type['show'] ?? '';
        $this->prepare(
            [
                'routes' => $route,
            ]
        );
        $records = Termin::with('details', 'terminPembayaran')->gridPembayaranStatusCompleted()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('#', function ($record) {
                return request()->start;
            })
            ->addColumn('pengajuan', function ($record) {
                if($record->aktiva){
                    return $record->aktiva->code . '<br>' . $record->aktiva->getTglPengajuanLabelAttribute();
                }else{
                    return $record->pengajuanSgu->code . '<br>' .$record->pengajuanSgu->getTglPengajuanLabelAttribute();
                }
            })
            ->addColumn('jenis_pembayaran', function ($record) {
                return Base::makeLabel(ucwords('termin'), 'warning');
            })
            ->addColumn('nominal', function ($record) {
                if($record->aktiva){
                    return number_format($record->aktiva->getTotalHarga(), 0, ',', '.');
                }else{
                    return number_format($record->pengajuanSgu->rent_cost, 0, ',', '.');
                }
            })
            ->addColumn('sisa_tagihan', function ($record) {
                if($record->aktiva){
                    $sisa = $record->aktiva->getTotalHarga() - $record->details()->where('status', 'Terbayar')->sum('total');
                }else{
                    $sisa = $record->pengajuanSgu->rent_cost - $record->details()->where('status', 'Terbayar')->sum('total');
                }
                return number_format($sisa, 0, ',', '.');
            })
            ->addColumn('struct', function ($record) {
                return $record->struct ? $record->struct->name : '';
            })
            ->addColumn('status', function ($record) use ($user) {
                $terminPembayaran = $record->terminPembayaran;
                if(!$terminPembayaran){
                    return "";
                }else{
                    return $terminPembayaran->labelStatus($terminPembayaran->status ?? 'new');
                }
            })
            ->addColumn('updated_by', function ($record) {
                $terminPembayaran = $record->terminPembayaran;
                if(!$terminPembayaran){
                    return "";
                }else{
                    if($terminPembayaran->status != "new"){
                        return $terminPembayaran->createdByRaw();
                    }else{
                        return "";
                    }
                }
            })
            ->addColumn('action', function ($record) use ($user, $route) {
                $actions = [];
                $actions[] = [
                    'type' => 'print',
                    'page' => 'true',
                    'id' => $record->id,
                    'url' => route($route . '.print', $record->id)
                ];

                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(['jenis_pembayaran','action','updated_by','status', 'pengajuan'])
            ->make(true);
    }
}
