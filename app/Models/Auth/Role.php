<?php

namespace App\Models\Auth;

use App\Imports\Setting\RoleImport;
use App\Models\Auth\User;
use App\Models\Globals\Approval;
use App\Models\Globals\MenuFlow;
use App\Models\Globals\TempFiles;
use App\Models\Traits\HasFiles;
use App\Models\Traits\RaidModel;
use App\Models\Traits\ResponseTrait;
use App\Models\Traits\Utilities;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use RaidModel, Utilities, ResponseTrait;
    use HasFiles;

    // Model Role menggunakan beberapa trait, yaitu RaidModel, Utilities, ResponseTrait, dan HasFiles. Trait-trait ini memungkinkan model untuk mengakses fitur-fitur yang telah didefinisikan di dalamnya, seperti penggunaan utilitas, pengaturan respon, dan manipulasi berkas (file).

    /** SCOPE **/
    public function scopeGrid($query)
    {
        return $query->latest();
    }

    public function scopeFilters($query)
    {
        return $query->filterBy('name');
    }

    /** RELATIONS **/
    public function menuFlows()
    {
        return $this->hasMany(MenuFlow::class, 'role_id');
    }

    /** SAVE DATA **/
    public function handleStoreOrUpdate($request)
    {
        $this->beginTransaction();
        try {
            $this->name = $request->name;
            $this->save();
            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleDestroy()
    {
        $this->beginTransaction();
        try {
            if (!$this->canDeleted()) {
                return $this->rollback(__('base.error.related'));
            }
            $this->saveLogNotify();
            $this->delete();

            return $this->commitDeleted();
        } catch (\Exception $e) {
            return $this->rollbackDeleted($e);
        }
    }

    public function handleGrant($request)
    {//handle perizinan hak akses
        $this->beginTransaction();
        try {
            $this->syncPermissions($request->check ?? []);
            // $this->syncPermissions($request->check ?? []): Baris ini memanggil method syncPermissions() pada objek saat ini ($this) dengan argumen $request->check ?? []. Ini digunakan untuk menyinkronkan (sinkronisasi) izin (permissions) pada objek dengan izin yang diberikan dalam $request->check. Operator ?? digunakan untuk memberikan nilai default berupa array kosong [] jika $request->check tidak didefinisikan atau bernilai null.
            $this->saveLogNotify();
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            
            $this->update([
                'updated_by' => auth()->user()->id
            ]);

            // app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();: Baris ini memanggil method forgetCachedPermissions() pada objek PermissionRegistrar dari pustaka Spatie\Permission. Tujuan dari baris ini adalah untuk menghapus izin-izin yang sudah disimpan dalam cache untuk memastikan izin yang terbaru diperbarui.
            return $this->commitSaved(['redirectTo' => route($request->routes . '.index')]);
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function handleImport($request)
    {
        $this->beginTransaction();
        try {
            $file = TempFiles::find($request->uploads['temp_files_ids'][0]);
            if (!$file || !\Storage::disk('public')->exists($file->file_path)) {
                $this->rollback('File tidak tersedia!');
            }

            \Excel::import(new RoleImport, \Storage::disk('public')->path($file->file_path));

            $this->saveLogNotify();

            return $this->commitSaved();
        } catch (\Exception $e) {
            return $this->rollbackSaved($e);
        }
    }

    public function saveLogNotify()
    {
        $data = $this->name;
        $routes = request()->get('routes');
        switch (request()->route()->getName()) {
            case $routes . '.store':
                $this->addLog('Membuat Data ' . $data);
                break;
            case $routes . '.update':
                $this->addLog('Mengubah Data ' . $data);
                break;
            case $routes . '.destroy':
                $this->addLog('Menghapus Data ' . $data);
                break;
            case $routes . '.grant':
                $this->addLog('Mengubah Hak Akses Role ' . $data);
                break;
            case $routes . '.importSave':
                auth()->user()->addLog('Import Data Hak Akses Role');
                break;
        }
    }

    /** OTHER FUNCTION **/
    public function canDeleted()
    {
        if (in_array($this->id, [1, 2, 3])) return false;
        if ($this->users()->exists()) return false;
        if ($this->menuFlows()->exists()) return false;
        if (Approval::where('role_id', $this->id)->exists()) return false;

        return true;
    }
}
