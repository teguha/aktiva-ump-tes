<?php 

namespace App\Models\Traits;

use App\Models\Auth\User;
use App\Models\Globals\Approval;
use App\Models\Globals\MenuFlow;

trait HasApprovals
{
    /** Approval by all module **/
    public function approvals()
    {
        return $this->morphMany(Approval::class,'target');
    }

    /** Approval by specific module **/
    public function approval($module)
    {
        return $this->approvals()->whereModule($module);
    }

//     approvals(): Ini adalah metode yang mendefinisikan relasi "morphMany". Metode morphMany digunakan untuk mendefinisikan hubungan "one-to-many polymorphic" di Laravel. Artinya, model ini memiliki banyak entitas "Approval" yang berhubungan dengannya, tetapi entitas-entitas tersebut tidak terbatas pada satu model saja, melainkan dapat berhubungan dengan beberapa model yang berbeda.

// Parameter pertama dari metode morphMany adalah nama model target yang akan dihubungkan, dalam kasus ini "Approval::class". Parameter kedua adalah nama kolom polimorfik yang akan digunakan untuk menyimpan informasi tentang tipe model target (dalam hal ini, kolom "target" pada tabel "Approval" akan menyimpan tipe model yang berhubungan).

// Jadi, dengan metode approvals(), model ini akan memiliki banyak entitas "Approval" yang terhubung dengannya melalui kolom polimorfik "target".

// approval($module): Ini adalah metode yang digunakan untuk mendapatkan entitas "Approval" berdasarkan modul tertentu.

// Metode ini menggunakan metode approvals() yang telah didefinisikan sebelumnya untuk melakukan query. Kemudian, menggunakan metode whereModule($module) untuk menambahkan kondisi pada query tersebut. Di sini, $module adalah parameter yang diteruskan ke dalam metode. Metode whereModule digunakan untuk memfilter entitas "Approval" berdasarkan nilai kolom "module" pada tabel "Approval" yang sesuai dengan nilai $module.

// Dengan demikian, metode approval($module) digunakan untuk mendapatkan entitas "Approval" yang terkait dengan model ini dan memiliki nilai kolom "module" yang sama dengan nilai $module.

    /** Use this function when submit **/
    public function generateApproval($module, $upgrade = false)
    {
        if ($this->approval($module . $upgrade ? '_upgrade' : '')->exists()) {
            // return $this->resetStatusApproval($module . $upgrade ? '_upgrade' : '');
            $this->approvals()->delete();
        }

        $flows = MenuFlow::hasModule($module)->orderBy('order')->get();
        if (!$flows->count()) {
            return $this->responseError(
                [
                    'message' => 'Flow Approval tidak terdapat!'
                ]
            );
        }

        $results = [];
        foreach ($flows as $item) {
            $results[] = new Approval(
                [
                    'module'    => $module . ($upgrade ? '_upgrade' : ''),
                    'role_id'   => $item->role_id,
                    'order'     => $item->order,
                    'type'      => $item->type,
                    'status'    => 'new',
                ]
            );
        }

        return $this->approval($module)->saveMany($results);
    }

    public function resetStatusApproval($module)
    {
        return $this->approval($module)
                    ->update([
                        'status'      => 'new',
                        'user_id'     => null,
                        'position_id' => null,
                        'note'        => null,
                        'approved_at' => null,
                    ]);
    }

    /** Use this function before submit **/
    public function getFlowApproval($module)
    {
        if ($this->approval($module)->exists()) {
            return $this->approval($module)
                ->orderBy('order')
                ->get()
                ->groupBy('order');
        }

        return MenuFlow::whereHas('menu',function ($q) use ($module) {
                $q->where('module', $module);
            }
        )
            ->orderBy('order')
            ->get()
            ->groupBy('order');
    }

    // 'menu', function ($q) use ($module) {: Ini adalah definisi hubungan antara model MenuFlow dan model Menu. 'menu' adalah nama fungsi relasi antara dua model tersebut. function ($q) use ($module) { ... } adalah closure atau anonymous function yang digunakan untuk menentukan kondisi pada relasi. $q adalah objek query builder untuk model Menu.

    public function rejected($module)
    {
        return $this->approval($module)->whereStatus('rejected')->latest()->first();
    }

    public function firstNewApproval($module)
    {
        return $this->approval($module)->whereStatus('new')->orderBy('order')->first();
    }

    /** Check auth user can action approval by specific module **/
    public function checkApproval($module)
    {
        if ($new = $this->firstNewApproval($module)) {
            $user = auth()->user();
            return $this->approval($module)
                        ->where('order', $new->order)
                        ->whereStatus('new')
                        ->whereIn('role_id', $user->getRoleIds())
                        ->exists();
        }

        return false;
    }

    public function getNewUserIdsApproval($module)
    {
        $role_ids = [];
        $temps = $this->approval($module)
            // ->whereStatus('new')
            ->orderBy('order')
            ->get();
        if ($temps->count() == 1) {
            if ($new = $this->firstNewApproval($module)) {
                $role_ids = $this->approval($module)
                    ->where('order', $new->order)
                    ->whereStatus('new')
                    ->pluck('role_id')
                    ->toArray();
            }
        } else {
            foreach ($temps as $temp) {
                $temp_role = $this->approval($module)
                    ->where('order', $temp->order)
                    // ->whereStatus('new')
                    ->pluck('role_id')
                    ->first();
                array_push($role_ids, $temp_role);
            }
        }



        return User::whereHas('roles', function ($q) use ($role_ids) {
            $q->whereIn('id', $role_ids);
        })
            ->pluck('id')
            ->toArray();
    }

    /** Reject data by specific module by specific module **/
    public function rejectApproval($module, $note)
    {
        if ($new = $this->firstNewApproval($module)) {
            $user = auth()->user();
            return $this->approval($module)
                        ->where('order', $new->order)
                        ->whereStatus('new')
                        ->whereIn('role_id', $user->getRoleIds())
                        ->update([
                            'status' => 'rejected',
                            'user_id' => $user->id,
                            'position_id' => $user->position_id,
                            'note' => $note,
                            'approved_at' => null,
                        ]);
        }
    }

    public function reviseApproval($module, $note)
    {
        if ($new = $this->firstNewApproval($module)) {
            $user = auth()->user();
            return $this->approval($module)
                        ->where('order', $new->order)
                        ->whereStatus('new')
                        ->whereIn('role_id', $user->getRoleIds())
                        ->update([
                            'status' => 'revision',
                            'user_id' => $user->id,
                            'position_id' => $user->position_id,
                            'note' => $note,
                            'approved_at' => null,
                        ]);
        }
    }

    /** Approve data by specific module **/
    public function approveApproval($module, $note = null)
    {
        switch ($module) {
            case "pengajuan_pembelian.pengajuan": 
                $status = "authorized";
                break;
            default:
                $status = "approved";
                break;
        }
        
        if ($new = $this->firstNewApproval($module)) {
            $user = auth()->user();
            return $this->approval($module)
                        ->where('order', $new->order)
                        ->whereStatus('new')
                        ->whereIn('role_id', $user->getRoleIds())
                        ->update([
                            'status' => $status,
                            'user_id' => $user->id,
                            'position_id' => $user->position_id,
                            'note' => $note,
                            'approved_at' => now(),
                        ]);
        }
    }
}