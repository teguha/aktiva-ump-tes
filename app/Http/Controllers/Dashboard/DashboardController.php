<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Aktiva\PembelianAktiva;
use App\Models\PelepasanAktiva\PenghapusanAktiva;
use App\Models\Sgu\PengajuanSgu;
use App\Models\Termin\Termin;
use App\Models\Ump\PengajuanUmp;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $module =  'dashboard';
    protected $routes =  'dashboard';
    protected $views =  'dashboard';

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'title' => 'Dashboard',
            ]
        );
    }
    // tampilan halaman home
    public function index()
    {
        $user = auth()->user();
        if ($user->status != 'active') { // jika status user tidak aktif
            return $this->render($this->views . '.nonactive');
        }
        // jika akses dashbord tidak dibolehkan oleh ke user atau user tidak memiliki role
        if (!$user->checkPerms('dashboard.view') || !$user->roles()->exists()) {
            return abort(403);
        }
        // jika tidak ada error maka di return ke dahbord.index
        return $this->render($this->views . '.index');
    }

    // public function setLang($lang)
    // {
    //     if (\Cache::has('userLocale')) {
    //         \Cache::forget('userLocale');
    //     }
    //     \Cache::forever('userLocale', $lang);
    //     return redirect()->back();
    // }
    
    // tampilan card
    public function progress(Request $request)
    {
        // Pengaduan WBS
        // $total = Pengaduan::count();
        // $compl = Pengaduan::where('status', 'completed')->count();
        // $percent = ($compl > 0 && $total > 0) ? round(($compl / $total * 100), 0) : 0;
        $pembelian_total = PembelianAktiva::where('status', '!=', 'cancelled')->count();
        $pembelian_completed = PembelianAktiva::where('status', 'waiting verification')->count();              //dibulatkan dan mengambil 2 angka di belakang koma
        $pembelian_percent = $pembelian_total > 0 && $pembelian_completed > 0 ? round($pembelian_completed / $pembelian_total, 2)*100 : 0;
        //contoh 
        // persen = round(5/10,2) *100
        //50 %
        $cards[] = [
            'name' => 'pembelian',
            'total' => $pembelian_total,
            'completed' => $pembelian_completed,
            'percent' => $pembelian_percent,
        ];

        $sgu_total = PengajuanSgu::where('status', '!=', 'cancelled')->count();
        $sgu_completed = PengajuanSgu::where('status', 'waiting verification')->count();
        $sgu_percent = $sgu_total > 0 && $sgu_completed > 0 ? round($sgu_completed / $sgu_total, 2)*100 : 0;
        $cards[] = [
            'name' => 'sgu',
            'total' => $sgu_total,
            'completed' => $sgu_completed,
            'percent' => $sgu_percent,
        ];

        $pelepasan_total = PenghapusanAktiva::where('status', '!=', 'cancelled')->count();
        $pelepasan_completed = PenghapusanAktiva::where('status', 'completed')->count();
        $pelepasan_percent = $pelepasan_total > 0 && $pelepasan_completed > 0 ? round($pelepasan_completed / $pelepasan_total, 2)*100 : 0;
        $cards[] = [
            'name' => 'pelepasan',
            'total' => $pelepasan_total,
            'completed' => $pelepasan_completed,
            'percent' => $pelepasan_percent,
        ];

        // $pelepasan_total = MutasiAktiva::where('status', '!=', 'cancelled')->count();
        // $pelepasan_completed = MutasiAktiva::where('status', 'completed')->count();
        // $pelepasan_percent = $pelepasan_total > 0 && $pelepasan_completed > 0 ? round($pelepasan_completed / $pelepasan_total, 2)*100 : 0;
        // $cards[] = [
        //     'name' => 'pelepasan',
        //     'total' => $pelepasan_total,
        //     'completed' => $pelepasan_completed,
        //     'percent' => $pelepasan_percent,
        // ];
        
        // data dijadikan bentuk json 
        return response()->json(
            [
                'data' => $cards
            ]
        );
    }

    // chart Uang Muka Pembayaran
    public function chartUmp(Request $request)
    {
        $request->merge(['year' => $request->stage_year ?? date('Y')]);

        // ['year' => $request->stage_year ?? date('Y')]: Ini adalah array yang berisi elemen 'year' yang akan digabungkan (merge) dengan data dalam permintaan ($request). 'year' adalah kunci (key) dalam array yang akan digunakan untuk menyimpan nilai.

        // $request->stage_year: Merupakan cara untuk mengakses nilai dari elemen 'stage_year' dalam objek permintaan ($request). Jadi, ini mencoba untuk mendapatkan nilai 'stage_year' dari permintaan.

        // ??: Ini adalah operator null coalescing. Jika nilai sebelumnya ($request->stage_year) adalah null atau tidak terdefinisi, maka nilai setelah operator ?? (date('Y')) akan digunakan sebagai nilai alternatif.

        // date('Y'): Ini adalah fungsi PHP yang mengembalikan tahun saat ini dalam format 4 digit. Misalnya, jika sekarang tahun 2023, maka fungsi ini akan mengembalikan string '2023'.

        $year = $request->stage_year;
        $title = '';

        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $completedData = PengajuanUmp::selectRaw('MONTH(date_ump) as month, COUNT(*) as total_completed')
        ->where('status', 'waiting verification')
        ->whereYear('date_ump', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $notCompletedData = PengajuanUmp::selectRaw('MONTH(date_ump) as month, COUNT(*) as total_not_completed')
        ->whereNotIn('status', ['waiting verification', 'draft', 'cancelled', 'new'])
        ->whereYear('date_ump', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $temp_data['completed'] = array_fill(0, 12, 0);
        $temp_data['not_completed'] = array_fill(0, 12, 0);

        foreach ($completedData as $row) {
            $temp_data['completed'][$row->month-1] = $row->total_completed;
        }


        foreach ($notCompletedData as $row) {
            $temp_data['not_completed'][$row->month-1] = $row->total_not_completed;
        }


        return [
            'title' => ['text' => $title],
            'series' => [
                [
                    'name' => 'Selesai',
                    'type' => 'column',
                    'data' => $temp_data['completed'],
                ],
                [
                    'name' => 'Progress',
                    'type' => 'column',
                    'data' => $temp_data['not_completed'],
                ],
            ],
            'xaxis' => ['categories' => $months],
            'colors' => ['#28C76F', '#F64E60'],
        ];
    }

    public function chartTermin(Request $request)
    {
        $request->merge(['year' => $request->stage_year ?? date('Y')]);

        $year = $request->stage_year;

        $title = '';

        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $completedData = Termin::join('pengajuan_pembelian', 'termin.code', '=', 'pengajuan_pembelian.id')
        ->selectRaw('MONTH(pengajuan_pembelian.date) as month, COUNT(*) as total_completed')
        ->where('termin.status', 'waiting verification')
        ->whereYear('date', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $notCompletedData = Termin::join('pengajuan_pembelian', 'termin.code', '=', 'pengajuan_pembelian.id')
        ->selectRaw('MONTH(pengajuan_pembelian.date) as month, COUNT(*) as total_not_completed')
        ->whereNotIn('termin.status', ['waiting verification', 'draft', 'cancelled'])
        ->whereYear('date', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $temp_data['completed'] = array_fill(0, 12, 0);
        $temp_data['not_completed'] = array_fill(0, 12, 0);

        foreach ($completedData as $row) {
            $temp_data['completed'][$row->month-1] = $row->total_completed;
        }

        foreach ($notCompletedData as $row) {
            $temp_data['not_completed'][$row->month-1] = $row->total_not_completed;
        }

        return [
            'title' => ['text' => $title],
            'series' => [
                [
                    'name' => 'Selesai',
                    'type' => 'area',
                    'data' => $temp_data['completed']
                ],
                [
                    'name' => 'Progress',
                    'type' => 'column',
                    'data' => $temp_data['not_completed'],
                ],
            ],
            'xaxis' => ['categories' => $months],
            'colors' => ['#28C76F', '#F64E60'],
        ];
    }

    public function chartSaran(Request $request)
    {
        $request->merge(['year' => $request->stage_year ?? date('Y')]);

        $year = $request->stage_year;

        // $data = Saran::countSaranForDashboard($year);
        // $title = 'Saran / Keluhan' . ' ' . $request->stage_year;
        $title = '';

        $data['total'][0] = 20;
        $data['total'][1] = 30;
        $data['total'][2] = 40;
        $data['total'][3] = 10;
        $data['total'][4] = 20;
        $data['total'][5] = 45;
        $data['total'][6] = 60;
        $data['total'][7] = 40;
        $data['total'][8] = 30;
        $data['total'][9] = 80;
        $data['total'][10] = 90;
        $data['total'][11] = 80;
        $data['completed'][0] = 10;
        $data['completed'][1] = 20;
        $data['completed'][2] = 20;
        $data['completed'][3] = 10;
        $data['completed'][4] = 40;
        $data['completed'][5] = 15;
        $data['completed'][6] = 10;
        $data['completed'][7] = 10;
        $data['completed'][8] = 10;
        $data['completed'][9] = 40;
        $data['completed'][10] = 30;
        $data['completed'][11] = 20;

        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        return [
            'title' => ['text' => $title],
            'series' => [
                [
                    'name' => 'Total',
                    'data' => $data['total']
                ],
                [
                    'name' => 'Completed',
                    'data' => $data['completed']
                ],


            ],
            'xaxis' => ['categories' => $months],
            'colors' => ['#28C76F'],
            'type' => 'bar'
        ];
    }

    public function chartUser(Request $request)
    {
        $request->merge(['year' => $request->stage_year ?? date('Y')]);

        $year = $request->stage_year;
        // $data = Activity::countUserAccessForDashboard($year);
        // $title = 'User Akses' . ' ' . $request->stage_year;
        $title = '';

        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        $data['total'][0] = 20;
        $data['total'][1] = 30;
        $data['total'][2] = 40;
        $data['total'][3] = 10;
        $data['total'][4] = 20;
        $data['total'][5] = 45;
        $data['total'][6] = 60;
        $data['total'][7] = 40;
        $data['total'][8] = 30;
        $data['total'][9] = 80;
        $data['total'][10] = 90;
        $data['total'][11] = 80;

        return [
            'title' => ['text' => $title],
            'series' => [
                [
                    'name' => 'Total',
                    'type' => 'bar',
                    'data' => $data['total']
                ],

            ],
            'xaxis' => ['categories' => $months],
            'colors' => ['#28C76F'],
            'type' => 'bar'
            // 'colors' => ['#E4E6EF', '#28C76F', '#F64E60', '#FF9F43'],
        ];
    }
}
