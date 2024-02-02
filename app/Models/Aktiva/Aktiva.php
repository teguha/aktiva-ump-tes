<?php

namespace App\Models\Aktiva;

use App\Models\Master\Org\OrgStruct;
use App\Models\Model;
use App\Models\MutasiAktiva\MutasiAktivaDetail;
use App\Models\PelepasanAktiva\PenghapusanAktivaDetail;
use App\Models\PelepasanAktiva\PenjualanAktivaDetail;
use App\Models\Pemeriksaan\PemeriksaanDetail;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use Carbon\Carbon;


class Aktiva extends Model
{
    use HasFiles, HasApprovals;

    protected $table = 'trans_aktiva';

    protected $fillable = [
        'pembelian_aktiva_detail_id',
        'code', //id aset
        'struct_id',
        'mutasi_aktiva_detail_id',
    ];

    protected $dates = [
    ];

    /*******************************
     ** MUTATOR
     *******************************/
    /*******************************
     ** ACCESSOR
     *******************************/
    /*******************************
     ** RELATION
     *******************************/
    function mutasiAktivaDetail() {
        return $this->belongsTo(MutasiAktivaDetail::class, 'mutasi_aktiva_detail_id');
    }
    function mutasiAktivaDetails() {
        return $this->hasMany(MutasiAktivaDetail::class, 'aktiva_id');
    }
    function pembelianAktivaDetail() {
        return $this->belongsTo(PembelianAktivaDetail::class, 'pembelian_aktiva_detail_id');
    }
    function penghapusanAktivaDetail() {
        return $this->hasOne(PenghapusanAktivaDetail::class, 'aktiva_id');
    }
    function pemeriksaanDetail() {
        return $this->hasOne(PemeriksaanDetail::class, 'aktiva_id');
    }
    function penjualanAktivaDetail() {
        return $this->hasOne(PenjualanAktivaDetail::class, 'aktiva_id');
    }
    function struct() {
        return $this->belongsTo(OrgStruct::class, 'struct_id');
    }
    /*******************************
     ** SCOPE
     *******************************/
    /*******************************
     ** SAVING
     *******************************/
    public function saveLogNotify()
    {
        $data = 'Pengajuan Pembelian pada ' . date('d/m/Y, H:i');
        $routes = request()->get('routes');
        switch (request()->route()->getName()) {
            case $routes . '.detailStore':
                $this->addLog('Membuat Detail Data ' . $data);
                break;
            case $routes . '.detailUpdate':
                $this->addLog('Mengubah Detail Data ' . $data);
                break;
            case $routes . '.detailDestroy':
                $this->addLog('Mengubah Detail Data ' . $data);
                break;
        }
    }

    public function checkAction($action, $perms)
    {
        $user = auth()->user();
        switch ($action) {
            case 'create':
                return $user->checkPerms($perms . '.create');

            case 'edit':
                $checkStatus = in_array($this->status, ['new', 'draft']);
                return $checkStatus && $user->checkPerms($perms . '.edit');

            case 'show':
            case 'history':
                return $user->checkPerms($perms . '.view');

            case 'delete':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.delete');
            case 'revision':
                return $this->status == 'revision' && $user->checkPerms($perms . '.edit');
            case 'tracking':
                $checkApproval = $this->approval(request()->get('module'))->exists();
                return $checkApproval && $user->checkPerms($perms . '.view');
                break;
            case 'print':
                $checkApproval = $this->approval(request()->get('module'))->exists();
                return $checkApproval && $user->checkPerms($perms . '.view');
                break;
            case 'approval':
                if ($this->checkApproval(request()->get('module'))) {
                    $checkStatus = in_array($this->status, ['waiting.approval']);
                    return $checkStatus && $user->checkPerms($perms . '.approve');
                }
                break;
        }

        return false;
    }
}
