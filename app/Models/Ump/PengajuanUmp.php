<?php

namespace App\Models\Ump;

use App\Models\Aktiva\PembelianAktiva;
use App\Models\Master\Bank\BankAccount;
use App\Models\Master\Org\OrgStruct;
use App\Models\Model;
use App\Models\Sgu\PengajuanSgu;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use App\Models\PengajuanPembelian\PengajuanPembelian;
use App\Models\Globals\MenuFlow;
use App\Models\Globals\Approval;
use App\Models\Auth\Role;
use App\Models\UMP\PjUMP;
use App\Models\UMP\PerpanjanganUMP;
use App\Models\UMP\PembayaranUMP;
use App\Models\UMP\PembatalanUMP;
use Carbon\Carbon;

class PengajuanUmp extends Model
{
    use HasFiles, HasApprovals;

    protected $table = 'trans_ump_pengajuan';

    protected $fillable = [
        'aktiva_id',
        'code_sgu',
        'code_ump',
        'date_ump',
        'nominal_pembayaran',
        'rekening_id',
        'tgl_pembayaran',
        'tgl_jatuh_tempo',
        'perihal',
        'sentence_start',
        'sentence_end',
        'status',
        'struct_id'
    ];

    protected $dates = [
        'date_ump',
        'tgl_pembayaran',
        'tgl_jatuh_tempo',
    ];

    /*******************************
     ** MUTATOR
     *******************************/
    //Mutator adalah metode dalam model Eloquent yang memungkinkan Anda mengatur nilai atribut sebelum disimpan dalam basis data

    public function setTglPembayaranAttribute($value)
    {
        $this->attributes['tgl_pembayaran'] = Carbon::createFromFormat('d/m/Y', $value);
        // $this->attributes['tgl_pembayaran']: Ini adalah cara untuk mengatur nilai atribut tgl_pembayaran pada model. Dalam hal ini, tgl_pembayaran adalah nama kolom di tabel basis data yang sesuai dengan atribut model ini.

        // Metode createFromFormat mengambil format tanggal yang diberikan dan menciptakan objek Carbon dari tanggal yang sesuai.
    }

    public function setTglJatuhTempoAttribute($value)
    {
        $this->attributes['tgl_jatuh_tempo'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function setDateUmpAttribute($value)
    {
        $this->attributes['date_ump'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    /*******************************
     ** ACCESSOR
     *******************************/
    // Accessor adalah metode dalam model Eloquent yang memungkinkan Anda mengubah nilai atribut sebelum dikembalikan sebagai respons
    public function getShowTglPencairanAttribute()
    {
        return Carbon::parse($this->tgl_pencairan)->format('d/m/Y');
        //ambil data tgl pencairan dan ditampilkan
        // Carbon::parse($this->tgl_pencairan): Ini adalah cara untuk mengonversi tanggal yang ada dalam atribut tgl_pencairan menjadi objek Carbon
    }

    public function getShowTglPengajuanUmpAttribute()
    {
        return Carbon::parse($this->date_ump)->format('d/m/Y');
        
    }

    public function getIdPengajuanUmp()
    {
        //format 000001
        $last_id = 1;
        $last_record = PengajuanUmp::where('status', '!=', 'new')->orderBy('code_ump', 'desc')->first();
        //record terbaru diambil dari status bukan new dan diurutkan dengan code ump terbesar kemuian data urutan pertama di ambil
        if ($last_record) {        //gantti nila 0 yang muncul dari value $last_record->code_ump dengan string kosong   
            $last_id = (int)str_replace('0', '', $last_record->code_ump);
            // str_replace digunakan untuk menghapus karakter '0' dari $last_record->code_ump. str_replace('0', '', $last_record->code_ump) akan mengganti semua kemunculan karakter '0' dengan string kosong (menghapus karakter '0'). (int) digunakan untuk mengubah hasil dari str_replace menjadi integer. Jadi, variabel $last_id akan menyimpan ID terakhir yang telah dihapus karakter '0'.
            $last_id++;
        }               //id data , 6 angka , angka 0 disebelah kiri
        return str_pad($last_id, 6, '0', STR_PAD_LEFT);
        // format yang di return jadi seperti 000001 jika id = 1 
    }
    /*******************************
     ** RELATION
     *******************************/

    public function aktiva()
    { //1 pengajuan bisa memiliki 1 pembelian aktiva
        return $this->belongsTo(PembelianAktiva::class, 'aktiva_id');
    }

    public function pengajuanSgu()
    {//pengajuan UMP memeiliki 1 pengajuan SGU
        return $this->belongsTo(PengajuanSgu::class, 'code_sgu');
    }

    public function pjUmp()
    {
        return $this->hasOne(PjUmp::class, 'pengajuan_ump_id');
    }

    public function perpanjanganUmp()
    {
        return $this->hasOne(PerpanjanganUmp::class, 'pengajuan_ump_id');
    }

    public function pembayaranUMP(){
        return $this->hasOne(PembayaranUMP::class, 'pengajuan_ump_id');
    }

    public function pembatalanUMP(){
        return $this->hasOne(PembatalanUMP::class, 'pengajuan_ump_id');
        // Relasi "hasOne" menunjukkan bahwa satu entitas dari model saat ini memiliki satu entitas terkait dari model PembatalanUMP.
    }

    public function unitKerja() {
        return $this->belongsTo(OrgStruct::class, 'unit_kerja');
    }

    public function struct() {
        //1 pengajuan punya 1 organisasi struktur
        return $this->belongsTo(OrgStruct::class, 'struct_id');
    }

    public function rekening()
    { // 1 pengajuan  punya 1 rekening  bank account
        return $this->belongsTo(BankAccount::class, 'rekening_id');
    }

    /*******************************
     ** SCOPE
     *******************************/
    //filter tampilkan data terbaru
    public function scopeGrid($query)
    {
        return $query->latest();
    }
    
    //flter berdasarkan status = complete
    public function scopeGridStatusCompleted($query)
    {
        return $query->where('status', 'completed')->latest();
    }

    public function scopeFilters($query)
    {
        return $query
            ->filterBy(['status'])
            ->filterBy(['code'])
            // filterBy adalah fungsi yang didefinisikan di tempat lain, namun tidak ada dalam kode yang Anda berikan. Fungsi ini menerima sebuah array dari kriteria yang akan dijadikan filter untuk query.
            ->when(
                $date_start = request()->post('date_start'),
                function ($q) use ($date_start) {
                    $q->when(
                        $date_end = request()->post('date_end'),
                        function ($q) use ($date_start, $date_end) {
                            $date_start = Carbon::createFromFormat('d/m/Y', $date_start)->startOfDay();
                            $date_end = Carbon::createFromFormat('d/m/Y', $date_end)->endOfDay();
                            // $date_start = Carbon::createFromFormat('d/m/Y', $date_start)->startOfDay(): Di dalam blok fungsi anonymous tersebut, nilai $date_start akan dikonversi ke objek Carbon dengan format 'd/m/Y', dan waktu akan diatur ke awal hari (00:00:00).

                            // $date_end = Carbon::createFromFormat('d/m/Y', $date_end)->endOfDay(): Sama seperti sebelumnya, nilai $date_end (yang diperoleh dari request POST) akan dikonversi menjadi objek Carbon dengan format 'd/m/Y', dan waktu diatur ke akhir hari (23:59:59).
                            $q->whereHas('aktiva', function ($q) use ($date_start, $date_end) {
                                return $q->whereBetween('date', [$date_start, $date_end]);
                            }); // mengambil data activa sesuai tanggal pengajuuan date start dan date end
                            // ->whereHas('aktiva', function ($q) use ($date_start, $date_end) { ... }): whereHas digunakan untuk memfilter hasil query berdasarkan relasi. Dalam hal ini, kita memfilter hasil query berdasarkan entitas terkait 'aktiva'. Di dalam blok fungsi anonymous ini, query akan diberikan tambahan kondisi whereBetween untuk memfilter berdasarkan tanggal.
                        }
                    );
                }
            )
            ->when(
                $struct_id = request()->post('struct_id'),
                function ($q) use ($struct_id) {
                    $q->whereHas('aktiva', function ($q) use ($struct_id) {
                        return $q->where('struct_id', $struct_id);
                    });
                }
            );
    }

    /*******************************
     ** SAVING
     *******************************/
    public function handleStoreOrUpdate($request, $statusOnly = false)
    {
        $this->beginTransaction();
        try {
            $data = $request->all();
            if (!empty($request->nominal_pembayaran)) {
                $data['nominal_pembayaran'] = str_replace('Rp', '', str_replace('.', '', $request->nominal_pembayaran));
            }
            $this->fill($data);
            $this->status = 'draft';
            $this->save();
            $this->saveFilesByTemp($request->uploads, 'ump.pengajuan-ump', 'uploads');
            $this->saveLogNotify();

            if ($request->is_submit) {
                $this->handleSubmitSave($request);
            }

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public static function generateIdPengajuan()
    {
        $last = Self::where('code_ump', '!=', NULL)->whereYear('created_at', now()->format('Y'))->orderBy('id', 'desc')->first();
        $lastNumber = $last ? substr($last->code_ump, -4) : 0000;
        return '3' . Carbon::now()->format('Ym') . str_pad(intval($lastNumber) + 1, 4, 0, STR_PAD_LEFT);
    }

    public function handleDestroy()
    {
        $this->beginTransaction();
        try {
            $this->saveLogNotify();
            $this->delete();

            return $this->commitDeleted();
        } catch (\Exception $e) {
            return $this->rollbackDeleted($e);
        }
    }

    public function handleSubmitSave($request)
    {
        $this->beginTransaction();
        try {
            $this->update(['status' => 'waiting.approval']);
            $this->generateApproval($request->module); //need to generate approval manually , maybe generate permission manually too instead within seeder?
            $this->saveLogNotify();
            // $this->code_ump = Self::generateIdPengajuan();
            $this->update();

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleReject($request)
    {
        $this->beginTransaction();
        try {
            $this->rejectApproval($request->module, $request->note);
            $this->update(['status' => 'rejected']);
            $this->saveLogNotify();

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleApprove($request)
    {
        $this->beginTransaction();
        try {
            $this->approveApproval($request->module);
            if ($this->approvals()->whereIn('status', ['draft', 'rejected'])->count() == 0) {
                $this->update([
                    'status' => 'completed',
                ]);
                $this->savePerpanjanganUMP();
                $this->savePembayaranUMP();
                $this->savePembatalanUMP();
            }
            $this->saveLogNotify();

            $redirect = route(request()->get('routes') . '.index');
            $message = "Data berhasil diotorisasi";
            return $this->commitSaved(compact('redirect', 'message'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function savePerpanjanganUmp()
    {
        return PerpanjanganUmp::firstOrCreate(['pengajuan_ump_id' => $this->id, 'created_by' => $this->created_by]);
    }

    public function savePembayaranUMP(){
        return PembayaranUMP::firstOrCreate(['pengajuan_ump_id' => $this->id, 'created_by' => $this->created_by]);
    }

    public function savePembatalanUMP(){
        return PembatalanUMP::firstOrCreate(['pengajuan_ump_id' => $this->id, 'created_by' => $this->created_by]);
    }

    public function saveLogNotify()
    {
        $data = \Base::getModules(request()->get('module')) . ' pada ' . date('d/m/Y');
        $routes = request()->get('routes');
        switch (request()->route()->getName()) {
            case $routes . '.store':
                $this->addLog('Membuat Data ' . $data);
                break;
            case $routes . '.update':
                if ($this->status == 'waiting.approval') {
                    $this->addLog('Submit Data ' . $data);
                    $this->addNotify([
                        'message' => 'Menunggu Otorisasi ' . $data,
                        'url' => route($routes . '.approval', $this->id),
                        'user_ids' => $this->getNewUserIdsApproval(request()->get('module')),
                    ]);
                    break;
                } else {
                    $this->addLog('Mengubah Data ' . $data);
                }
                break;
            case $routes . '.destroy':
                $this->addLog('Menghapus Data ' . $data);
                break;
            case $routes . '.approve':
                $this->addLog('Menyetujui Data ' . $data);
                $this->addNotify([
                    'message' => 'Menunggu Otorisasi ' . $data,
                    'url' => route($routes . '.approval', $this->id),
                    'user_ids' => $this->getNewUserIdsApproval(request()->get('module')),
                ]);
                break;
            case $routes . '.verify':
                $this->addLog('Memverifikasi Data ' . $data);
                $this->addNotify([
                    'message' => 'Menunggu Pembayaran ' . $data,
                    'url' => route($routes . '.payment', $this->id),
                    'user_ids' => $this->findUserByRoles(['Kepala Departemen Treasuri']),
                ]);
                break;
            case $routes . '.pay':
                $this->addLog('Membayar Data ' . $data);
                $this->addNotify([
                    'message' => 'Menunggu Konfirmasi ' . $data,
                    'url' => route($routes . '.confirmation', $this->id),
                    'user_ids' => [$this->pic_staff]
                ]);
                break;
            case $routes . '.confirm':
                $this->addLog('Mengkonfirmasi ' . $data);
                break;
            case $routes . '.revise':
                $this->addLog('Data ' . $data . ' perlu direvisi');
                break;
            case $routes . '.cancel':
                $this->addLog('Data ' . $data . ' dibatalkan');
                break;
        }
    }

    public function checkAction($action, $perms, $record = null)
    {
        $user = auth()->user();
        switch ($action) {
            case 'edit':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.edit');
                break;
            case 'show':
                return $user->checkPerms($perms . '.view');
                break;
            case 'history':
                return $user->checkPerms($perms . '.view');
                break;
            case 'approval':
                $checkStatus = in_array($this->status, ['waiting.approval']);
                return $checkStatus && $user->checkPerms($perms . '.approve');
                break;
            case 'tracking':
                $checkApproval = $this->approval(request()->get('module'))->exists();
                return $checkApproval && $user->checkPerms($perms . '.view');
                break;
            case 'print':
                $checkApproval = $this->approval(request()->get('module'))->exists();
                return $checkApproval && $user->checkPerms($perms . '.view');
                break;
        }

        return false;
    }
}
