<?php

namespace App\Models\Aktiva;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Master\Org\OrgStruct;
use App\Models\Model;
use App\Models\Termin\Termin;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use App\Models\Ump\PengajuanUmp;
use Carbon\Carbon;

class PembelianAktiva extends Model
{
    use HasFiles, HasApprovals;
    // 1 trans pembelan activa punya banyak detail pembelian activa , ini menjadi struct pembelian
    protected $table = 'trans_pembelian_aktiva';

    protected $fillable = [
        'code',
        'date',
        'struct_id',
        'skema_pembayaran',
        'cara_pembayaran',
        'sentence_start',
        'sentence_end',
        'status',
    ];

    protected $dates = [
        'date'
    ];

    /*******************************
     ** MUTATOR
     *******************************/
    function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat('d/m/Y', $value) : null;
    }
    /*******************************
     ** ACCESSOR
     *******************************/
    public function getStructName()
    {
        return $this->struct ? ucwords($this->struct->name) : '';
    }

    public function getTglPengajuanLabelAttribute()
    {
        return !empty($this->date) ? Carbon::parse($this->date)->format('d/m/Y') : '-';
    }

    public function getJumlahPembelianAktiva()
    {
        $aset = $this->aset;
        if ($aset) {
            if ($aset->jenis_aset == "tangible") {
                return $this->tangibleAsset() ? $this->tangibleAsset()->sum('jumlah_unit_pembelian') : "-";
            }
            return $this->intangibleAsset() ? $this->intangibleAsset()->sum('jumlah_unit_pembelian') : "-";
        }
        return "-";
    }

    public function getTotalHarga()
    {// terhubung ke model termin 
        if ($this->details()->count() != 0) {
            return $this->details()->sum('total_harga');
        }
        return 0;
    }

    public function getSkemaPembayaran()
    {
        $skema_pembayaran = $this->skema_pembayaran ?? null;
        $result = '';

        switch ($skema_pembayaran) {
            case 'termin':
                $result = '<span class="badge badge-warning" style="color:white">Termin</span>';
                break;
            case 'ump';
                $result = '<span class="badge badge-primary" style="color:white">UMP</span>';
                break;
            default:
                $result = 'aa';
        }
        return $result;
    }

    /*******************************
     ** RELATION
     *******************************/
    public function struct()
    { //1 trans pembelian activa memiliki 1 organisasi struct yang melakukan pembelian
        return $this->belongsTo(OrgStruct::class, 'struct_id');
    }

    public function details()
    { // 1 trans pembelian activa memiliki banyak detail pembelian
        return $this->hasMany(PembelianAktivaDetail::class, 'pengajuan_pembelian_id');
    }

    public function itemName()
    {
        $aset = $this->aset;
        if ($aset == null) {
            return '';
        }
        if ($aset->jenis_aset == "tangible") {
            return $this->tangibleAsset->nama_aktiva;
        }
        return $this->intangibleAsset->nama_aktiva;
    }

    /*******************************
     ** SCOPE
     *******************************/
    public function scopeGrid($query)
    {
        return $query;
    }

    public function scopeGridStatusCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFilters($query)
    {
        return $query
            ->filterBy(['code',])->filterBy(
            [
                'struct_id',
                'status',
            ],
            '='
        );
    }

    public function scopeMonitoringFilters($query)
    {
        $request = request();
        return $query
            ->when(
                $id_aset = $request->id_aset,
                function ($q) use ($id_aset) {
                    $q->where('code', 'like', '%' . $id_aset . '%');
                }
            )->when(
                $thn_perolehan_aset = $request->thn_perolehan_aset,
                function ($q) use ($thn_perolehan_aset) {
                    $q->where('date', 'like', '%' . $thn_perolehan_aset . '%');
                }
            )->when(
                $jenis_aset = $request->jenis_aset,
                function ($q) use ($jenis_aset) {
                    $q->whereHas('aset', function ($q) use ($jenis_aset) {
                        $q->where('jenis_aset', $jenis_aset);
                    });
                }
            )->when(
                $all_aset = $request->all_aset,
                function ($q) use ($all_aset) {
                    $q->whereHas(
                        'aset',
                        function ($q) {
                            $q->when(
                                $all_aset = request()->all_aset,
                                function ($qq) use ($all_aset) {
                                    $qq->whereHas(
                                        'tangibleAsset',
                                        function ($q) {
                                            $q->when(
                                                $all_aset = request()->all_aset,
                                                function ($qq) use ($all_aset) {
                                                    $qq->where('merk', 'like', '%' . $all_aset . '%');
                                                    $qq->orWhere('nama_aktiva', 'like', '%' . $all_aset . '%');
                                                    $qq->orWhere('no_seri', 'like', '%' . $all_aset . '%');
                                                }
                                            );
                                        }
                                    );
                                    $qq->orWhereHas(
                                        'intangibleAsset',
                                        function ($q) {
                                            $q->when(
                                                $all_aset = request()->all_aset,
                                                function ($qq) use ($all_aset) {
                                                    $qq->where('merk', 'like', '%' . $all_aset . '%');
                                                    $qq->orWhere('nama_aktiva', 'like', '%' . $all_aset . '%');
                                                    $qq->orWhere('no_seri', 'like', '%' . $all_aset . '%');
                                                }
                                            );
                                        }
                                    );
                                }
                            );
                        }
                    );
                }
            )->when(
                $struct_id = $request->struct_id,
                function ($q) use ($struct_id) {
                    $q->whereHas(
                        'aset',
                        function ($q) {
                            $q->when(
                                $struct_id = request()->struct_id,
                                function ($qq) use ($struct_id) {
                                    $qq->whereHas(
                                        'tangibleAsset',
                                        function ($q) {
                                            $q->when(
                                                $struct_id = request()->struct_id,
                                                function ($qq) use ($struct_id) {
                                                    $qq->where('struct_id', $struct_id);
                                                }
                                            );
                                        }
                                    );
                                    $qq->orWhereHas(
                                        'intangibleAsset',
                                        function ($q) {
                                            $q->when(
                                                $struct_id = request()->struct_id,
                                                function ($qq) use ($struct_id) {
                                                    $qq->where('struct_id', $struct_id);
                                                }
                                            );
                                        }
                                    );
                                }
                            );
                        }
                    );
                }
            )->when(
                $skema_pembayaran = $request->skema_pembayaran,
                function ($q) use ($skema_pembayaran) {
                    $q->where('skema_pembayaran', 'like', '%' . $skema_pembayaran . '%');
                }
            )
            ->when(
                $status = $request->status,
                function ($q) use ($status) {
                    if ($status === 'aktif') {
                        $q->where('status', 'aktif');
                    } elseif ($status === 'pengajuan.pembelian') {
                        $q->where('status', 'Pengajuan Pembelian');
                    } elseif ($status === 'pengajuan.sewagunausaha') {
                        $q->where('status', 'Pengajuan Sewa Guna Usaha');
                    } elseif ($status === 'pengajuan.ump') {
                        $q->where('status', 'Pengajuan Pembayaran UMP');
                    } elseif ($status === 'pengajuan.termin') {
                        $q->where('status', 'Pengajuan Pembayaran Termin');
                    } elseif ($status === 'pengajuan.pelepasan') {
                        $q->where('status', 'Pengajuan Pelepasan');
                    } elseif ($status === 'pengajuan.mutasi') {
                        $q->where('status', 'Pengajuan Mutasi');
                    }
                }
            );
    }

    public function scopeLaporanFilters($query)
    {
        $request = request();
        return $query
            ->when(
                $id_aset = $request->id_aset,
                function ($q) use ($id_aset) {
                    $q->where('code', 'like', '%' . $id_aset . '%');
                }
            )->when(
                $thn_perolehan_aset = $request->thn_perolehan_aset,
                function ($q) use ($thn_perolehan_aset) {
                    $q->where('date', 'like', '%' . $thn_perolehan_aset . '%');
                }
            )->when(
                $jenis_aset = $request->jenis_aset,
                function ($q) use ($jenis_aset) {
                    $q->whereHas('aset', function ($q) use ($jenis_aset) {
                        $q->where('jenis_aset', $jenis_aset);
                    });
                }
            )->when(
                $all_aset = $request->all_aset,
                function ($q) use ($all_aset) {
                    $q->whereHas(
                        'aset',
                        function ($q) {
                            $q->when(
                                $all_aset = request()->all_aset,
                                function ($qq) use ($all_aset) {
                                    $qq->whereHas(
                                        'tangibleAsset',
                                        function ($q) {
                                            $q->when(
                                                $all_aset = request()->all_aset,
                                                function ($qq) use ($all_aset) {
                                                    $qq->where('merk', 'like', '%' . $all_aset . '%');
                                                    $qq->orWhere('nama_aktiva', 'like', '%' . $all_aset . '%');
                                                    $qq->orWhere('no_seri', 'like', '%' . $all_aset . '%');
                                                }
                                            );
                                        }
                                    );
                                    $qq->orWhereHas(
                                        'intangibleAsset',
                                        function ($q) {
                                            $q->when(
                                                $all_aset = request()->all_aset,
                                                function ($qq) use ($all_aset) {
                                                    $qq->where('merk', 'like', '%' . $all_aset . '%');
                                                    $qq->orWhere('nama_aktiva', 'like', '%' . $all_aset . '%');
                                                    $qq->orWhere('no_seri', 'like', '%' . $all_aset . '%');
                                                }
                                            );
                                        }
                                    );
                                }
                            );
                        }
                    );
                }
            )->when(
                $struct_id = $request->struct_id,
                function ($q) use ($struct_id) {
                    $q->whereHas(
                        'aset',
                        function ($q) use ($struct_id) {
                            $q
                                ->whereHas(
                                    'tangibleAsset',
                                    function ($q) use ($struct_id) {
                                        $q->where('struct_id', $struct_id);
                                    }
                                )
                                ->orWhereHas(
                                    'intangibleAsset',
                                    function ($q)  use ($struct_id) {
                                        $q->where('struct_id', $struct_id);
                                    }
                                );
                        }
                    );
                }
            )
            ->when(
                $skema_pembayaran = $request->skema_pembayaran,
                function ($q) use ($skema_pembayaran) {
                    $q->where('skema_pembayaran', 'like', '%' . $skema_pembayaran . '%');
                }
            )
            ->when(
                $status = $request->status,
                function ($q) use ($status) {
                    if ($status == '1') {
                        $q->where('status', 'aktif');
                    } elseif ($status === '0') {
                        $q->where('status', 'nonaktif');
                    }
                }
            );
    }

    /*******************************
     ** SAVING
     *******************************/
    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $data = $request->all();
            $this->skema_pembayaran = 'termin';
            $this->status = 'draft';
            $this->fill($data);
            $this->save();
            $this->saveLogNotify();
            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleSubmitDetail($request)
    {
        $this->beginTransaction();
        try {

            if ($this->details->count() == 0) {
                return $this->rollback(
                    [
                        'message' => 'Detail Aktiva tidak boleh kosong!'
                    ]
                );
            }
            // update kalimat pembuka dan kalimat penutup
            $this->update([
                'sentence_start' => $request->sentence_start,
                'sentence_end'  => $request->sentence_end
            ]);
            if ($request->is_submit) {
                // cek flow approval?
                if ($request->is_submit == 1) {
                    $flowApproval = $this->getFlowApproval($request->module);
                    if ($flowApproval->count() == 0) {
                        return $this->rollback(
                            [
                                'message' => 'Data Flow Approval tidak tersedia!'
                            ]
                        );
                    }
                }

                // redirect ke modal submit
                return $this->commitSaved(['redirectToModal' => route($request->routes . '.submit', $this->id)]);
            }
            $this->saveLogNotify();
            $redirect = route($request->routes . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleDetailStoreOrUpdate($request, PembelianAktivaDetail $detail)
    {
        $this->beginTransaction();
        try {
            $detail->fill($request->all());
            if (!empty($request->jumlah_unit_pembelian)) {
                $detail['jumlah_unit_pembelian'] = str_replace('.', '', $request->jumlah_unit_pembelian);
            }
            if (!empty($request->harga_per_unit)) {
                $detail['harga_per_unit'] = str_replace('Rp', '', str_replace('.', '', $request->harga_per_unit));
            }
            if (!empty($request->harga_per_unit) && !empty($request->jumlah_unit_pembelian)) {
                $detail['total_harga'] = intval($detail['harga_per_unit'] * $detail['jumlah_unit_pembelian']);
            }
            if ($request->tgl_pembelian) {
                $day = Carbon::createFromFormat("d/m/Y", $request->tgl_pembelian)->day;
                if ($day <= 15) {
                    $result = Carbon::createFromFormat("d/m/Y", $request->tgl_pembelian)->format('m/Y');
                } else {
                    $result = Carbon::createFromFormat("d/m/Y", $request->tgl_pembelian)->addMonths(1)->format('m/Y');
                }
                if ($request->jenis_asset == 'tangible') {
                    $detail['tgl_mulai_depresiasi'] =  Carbon::createFromFormat('d/m/Y', $day . "/" . $result);
                } else {
                    $detail['tgl_mulai_amortisasi'] =  Carbon::createFromFormat('d/m/Y', $day . "/" . $result);
                }
            }

            if ($request->masa_pakai) {
                $detail['masa_penggunaan'] = $request->masa_pakai;
            } else {
                $detail['masa_penggunaan'] = $request->masa_penggunaan;
            }
            
            $this->details()->save($detail);
            $this->status = 'draft';
            $this->save();
            $this->saveLogNotify();

            return $this->commitSaved(
                [
                    'redirect' => route($request->routes . '.detail', $this->id)
                ]
            );
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
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

    public function handleDetailDestroy(PembelianAktivaDetail $detail)
    {
        $this->beginTransaction();
        try {
            $this->saveLogNotify();
            $detail->delete();

            return $this->commitDeleted([
                'redirect' => route(request()->routes . '.detail', $this->id)
            ]);
        } catch (\Exception $e) {
            return $this->rollbackDeleted($e);
        }
    }

    public function handleSubmitSave($request)
    {
        $this->beginTransaction();
        try {
            $this->update(['status' => 'waiting.approval']);
            $this->generateApproval($request->module);
            $this->saveLogNotify();

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
            $this->update(['status' => 'cancelled']);
            $this->saveLogNotify();

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleRevision($request)
    {
        $this->beginTransaction();
        try {
            $this->rejectApproval($request->module, $request->note);
            $this->update(['status' => 'revision']);
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
                if ($this->skema_pembayaran === 'ump') {
                    $this->savePengajuanUmp();
                } elseif ($this->skema_pembayaran === 'termin') {
                    $this->saveTermin();
                }
                $di = 1;
                foreach ($this->details as $key => $detail) {
                    for ($i = 1; $i < $detail->jumlah_unit_pembelian + 1; $i++) {
                        $aktiva = new Aktiva;
                        $aktiva->pembelian_aktiva_detail_id = $detail->id;
                        $aktiva->struct_id      = $detail->struct_id;
                        $aktiva->code = str_pad($this->id, 3, 0, STR_PAD_LEFT)
                            . '-' . str_pad($di, 2, 0, STR_PAD_LEFT)
                            . '-' . str_pad($i, 2, 0, STR_PAD_LEFT);
                        $aktiva->save();
                    }
                    $di++;
                }
            }
            $this->saveLogNotify();

            $redirect = route(request()->get('routes') . '.index');
            return $this->commitSaved(compact('redirect'));
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function savePengajuanUmp()
    {
        return PengajuanUmp::firstOrCreate(['aktiva_id' => $this->id, 'struct_id' => $this->struct_id]);
    }

    public function saveTermin()
    {
        return Termin::firstOrCreate(['aktiva_id' => $this->id, 'struct_id' => $this->struct_id]);
    }

    public function saveLogNotify()
    {
        $data = 'Pengajuan Pembelian pada ' . date('d/m/Y, H:i');
        $routes = request()->get('routes');
        switch (request()->route()->getName()) {
            case $routes . '.store':
                $this->addLog('Membuat Data ' . $data);
                break;
            case $routes . '.detailStore':
                $this->addLog('Membuat Detail Data ' . $data);
                break;
            case $routes . '.updateSummary':
                $this->addLog('Mengubah Data ' . $data);
                break;
            case $routes . '.detailUpdate':
                $this->addLog('Mengubah Detail Data ' . $data);
                break;
            case $routes . '.destroy':
                $this->addLog('Menghapus Data ' . $data);
                break;
            case $routes . '.detailDestroy':
                $this->addLog('Mengubah Detail Data ' . $data);
                break;
            case $routes . '.submitSave':
                $this->addLog('Simpan & Submit Data ' . $data);
                $this->addNotify([
                    'message' => 'Waiting Approval ' . $data,
                    'url' => route($routes . '.approval', $this->id),
                    'user_ids' => $this->getNewUserIdsApproval(request()->get('module')),
                ]);
                break;
            case $routes . '.approve':
                $this->addLog('Menyetujui Data ' . $data);
                $this->addNotify([
                    'message' => 'Menunggu Verifikasi ' . $data,
                    'url' => route($routes . '.approval', $this->id),
                    'user_ids' => $this->getNewUserIdsApproval(request()->get('module')),
                ]);
                break;
            case $routes . '.reject':
                $this->addLog('Menolak Data ' . $data . ' dengan alasan: ' . request()->get('note'));
                break;
        }
    }
    private function findUserByRoles($roles)
    {
        $role_ids = Role::whereIn('name', $roles)->get()->pluck('id');
        return User::whereHas('roles', function ($q) use ($role_ids) {
            $q->whereIn('id', $role_ids);
        })
            ->pluck('id')
            ->toArray();
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
