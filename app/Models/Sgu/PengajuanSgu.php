<?php

namespace App\Models\Sgu;

use App\Models\Auth\User;
use App\Models\Master\Org\OrgStruct;
use App\Models\Master\SkemaPembayaran\SkemaPembayaran;
use App\Models\Model;
use App\Models\Sgu\DetailSguDepresiasi;
use App\Models\Termin\Termin;
use App\Models\Traits\HasApprovals;
use App\Models\Traits\HasFiles;
use App\Models\Ump\PengajuanUmp;
use Carbon\Carbon;

class PengajuanSgu extends Model
{
    use HasFiles;
    use HasApprovals;

    protected $table = 'trans_sgu';
    protected $fillable = [
        'code',
        'submission_date',
        'termin_id',
        'termin_date',
        'work_unit_id',
        'payment_scheme',

        'sentence_start',
        'sentence_end',
        'rent_location',
        'rent_start_date',
        'rent_end_date',
        'rent_time_period',

        'deposit',
        'rent_cost',
        'depreciation_start_date',
        'depreciation_end_date',
        'payment_date',
        'depreciation_total',

        'status',
        'notes'
    ];

    protected $dates = [
        'submission_date',
        'termin_date',
        'rent_start_date',
        'rent_end_date',
        'depreciation_start_date',
        'depreciation_end_date',
        'payment_date',
    ];

    // // /** MUTATOR **/
    public function setSubmissionDateAttribute($value)
    {
        $this->attributes['submission_date'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    // public function setRentStartDateAttribute($value)
    // {
    //     $this->attributes['rent_start_date'] = Carbon::createFromFormat('d/m/Y', $value);
    // }

    // public function setRentEndDateAttribute($value)
    // {
    //     $this->attributes['rent_end_date'] = Carbon::createFromFormat('d/m/Y', $value);
    // }

    public function setPaymentDateAttribute($value)
    {
        $this->attributes['payment_date'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    // harga
    public function setDepositAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['deposit'] = (int) $value;
    }

    public function setRentCostAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['rent_cost'] = (int) $value;
    }

    // public function setDepreciationTotalAttribute($value='')
    // {
    //     $value = str_replace(['.',','], '', $value);
    //     $this->attributes['depreciation_total'] = (int) $value;
    // }

    /*******************************
     ** RELATION
     *******************************/
    public function workUnit()
    {
        return $this->belongsTo(OrgStruct::class, 'work_unit_id');
    }

    public function details()
    {
        return $this->hasMany(DetailSguDepresiasi::class, 'work_unit_id');
    }

    public function paymentScheme()
    {
        return $this->belongsTo(SkemaPembayaran::class, 'payment_scheme_id');
    }

    public function scopeGrid($query)
    {
        return $query->latest();
    }

    public function scopeGridStatusCompleted($query)
    {
        return $query->where('status', 'completed')->latest();
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['code'])
            ->when(
                $date_start = request()->post('date_start'),
                function ($q) use ($date_start) {
                    $q->when(
                        $date_end = request()->post('date_end'),
                        function ($q) use ($date_start, $date_end) {
                            $date_start = Carbon::createFromFormat('d/m/Y', $date_start)->startOfDay();
                            $date_end = Carbon::createFromFormat('d/m/Y', $date_end)->endOfDay();
                            $q->whereBetween('submission_date', [$date_start, $date_end]);
                        }
                    );
                }
            )
            ->filterBy(['work_unit_id'])
            ->when(
                $status = request()->post('status'),
                function ($q) use ($status) {
                    if ($status == 5) {
                        $q->where('status', 0);
                    } else {
                        $q->where('status', $status);
                    }
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
            $this->payment_scheme = 'termin';
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
        $data = $request->all();
        if (!empty($request->rent_cost)) {
            $data['rent_cost'] = str_replace('.', '', $request->rent_cost);
        }
        if (!empty($request->deposit)) {
            $data['deposit'] = str_replace('.', '', $request->deposit);
        }
        if (!empty($request->rent_start_date)) {
            $data['rent_start_date'] = Carbon::createFromFormat('d/m/Y', $request->rent_start_date);
            $rent_start_date =  Carbon::createFromFormat('d/m/Y', $request->rent_start_date);
            $data['rent_end_date'] = $rent_start_date->addMonths($request->rent_time_period)->format('Y-m-d');
            $data['depreciation_end_date'] = $data['rent_end_date'];
            $day = $data['rent_start_date']->day;
            if ($day <= 15) {
                $result = $data['rent_start_date'];
            } else {
                $result = $data['rent_start_date']->addMonths(1);
            }
            $data['depreciation_start_date'] = $result;
        }
        if (!empty($request->deposit)) {
            $data['deposit'] = str_replace('.', '', $request->deposit);
        }
        $this->fill($data);
        $this->save();
        $this->saveLogNotify();

        // redirect ke modal submit
        if ($request->is_submit == 1) {
            return $this->commitSaved(
                [
                    'redirectToModal' => route($request->routes . '.submit', $this->id)
                ]
            );
        }

        $redirect = route(request()->get('routes') . '.index');
        return $this->commitSaved(compact('redirect'));
    }

    public static function generateIdPengajuan()
    {
        $last = Self::where('code', '!=', NULL)->whereYear('created_at', now()->format('Y'))->orderBy('id', 'desc')->first();
        $lastNumber = $last ? substr($last->code, -4) : 0000;
        return '2' . Carbon::now()->format('Ym') . str_pad(intval($lastNumber) + 1, 4, 0, STR_PAD_LEFT);
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


    /*******************************
     ** OTHER FUNCTIONS
     *******************************/
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

    public function handleApprove($request)
    {
        $this->beginTransaction();
        try {
            $this->approveApproval($request->module);
            if ($this->approvals()->whereIn('status', ['draft', 'rejected'])->count() == 0) {
                $this->update(['status' => 'completed']);
                if ($this->payment_scheme == 'termin') {
                    $termin = Termin::firstOrCreate(['code_sgu' => $this->id]);
                    $termin->struct_id = $this->work_unit_id;
                    $termin->update();
                } elseif ($this->payment_scheme == 'ump') {
                    $termin = PengajuanUmp::firstOrCreate(['code_sgu' => $this->id]);
                    $termin->struct_id = $this->work_unit_id;
                    $termin->update();
                }
            }
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

    // ------------------------------------------------- //
    // Save log activity
    // ------------------------------------------------- //
    public function saveLogNotify()
    {
        $data = 'Pengajuan Sewa Guna Usaha';
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
                } else {
                    $this->addLog('Mengubah Data ' . $data);
                }
                break;
            case $routes . '.updateSummary':
                $this->addLog('Mengubah Data ' . $data);
                break;
            case $routes . '.approve':
                $this->addLog('Menyetujui Data ' . $data);
                $this->addNotify([
                    'message' => 'waiting.approval ' . $data,
                    'url' => route($routes . '.approval', $this->id),
                    'user_ids' => $this->getNewUserIdsApproval(request()->get('module')),
                ]);
                break;
            case $routes . '.reject':
                if (request()->get('action') != 'revision') {
                    $this->addLog('Menolak Data ' . $data . ' dengan alasan: ' . request()->get('note'));
                } else {
                    $this->addLog('Mengajukan Revisi Data ' . $data . 'dengan alasan: ' . request()->get('note'));
                }
                break;
            case $routes . '.destroy':
                $this->addLog('Menghapus Data ' . $data);
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

            case 'revision':
                $checkStatus = in_array($this->status, ['revision']);
                return $checkStatus && $user->checkPerms($perms . '.edit');

            case 'show':
                return $user->checkPerms($perms . '.view');

            case 'delete':
                $checkStatus = in_array($this->status, ['new', 'draft']);
                return $checkStatus && $user->checkPerms($perms . '.delete');

            case 'history':
                return $user->checkPerms($perms . '.view');

            case 'approval':
                if ($this->checkApproval(request()->get('module'))) {
                    $checkStatus = in_array($this->status, ['waiting.approval']);
                    return $checkStatus && $user->checkPerms($perms . '.approve');
                }
                break;

            case 'print':
                $checkStatus = in_array($this->status, ['waiting.approval', 'waiting verification', 'completed']);
                return $checkStatus && $user->checkPerms($perms . '.view');
            case 'tracking':
                $checkApproval = $this->approval(request()->get('module'))->exists();
                return $checkApproval && $user->checkPerms($perms . '.view');
        }
        return false;
    }

    public function saveFiles($request)
    {
        $this->saveFilesByTemp($request->uploads, $request->module, 'uploads');
    }

    public function getTglPengajuanLabelAttribute()
    {
        return !empty($this->submission_date) ? Carbon::parse($this->submission_date)->format('d/m/Y') : '-';
    }

    public function calculateDepresiasiPerBulan($time, $max)
    {
        return ($this->calculateNilaiBuku(0, $max) / 12);
    }

    public function calculateNilaiBuku($time, $max)
    {
        if ($time > 0) {
            return $this->calculateNilaiBuku($time - 1, $max) - $this->calculateDepresiasi($time - 1, $max);
        }
        return $this->rent_cost;
    }

    public function calculateMasaManfaat($time, $max)
    {
        $masa_manfaat = 12 - (int) Carbon::parse($this->depreciation_start_date)->month + 1;
        if ($time == 0) {
            return $masa_manfaat;
        } else if ($time != $max) {
            return 12;
        }
        return $this->masa_pakai - 12 * ($time - 1) - $masa_manfaat;
    }

    public function calculateDepresiasi($time, $max)
    {
        return $this->calculateMasaManfaat($time, $max) * $this->calculateDepresiasiPerBulan($time, $max);
    }
}
