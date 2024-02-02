<?php

namespace App\Models\Traits;

use App\Models\Globals\Files;
use App\Models\Globals\TempFiles;

trait HasFiles
{
    /** Files by specific module **/
    public function files($module = null)
    {
        return $this->morphMany(Files::class,'target')
                    ->when(!is_null($module), function ($q) use ($module) {
                        $q->whereModule($module);
                    });
    }

    public function saveFilesByTemp($data, $module = null, $flag = null)
    {
        $filesIds = [];
        // Check id sys_files yang sudah ada
        if (!empty($data['files_ids']) && is_array($data['files_ids'])) {
            foreach ($data['files_ids'] as $file_id) {
                $filesIds[] = $file_id;
            }
        }

        // Tambah sys_files dari sys_files_temp
        if (!empty($data['temp_files_ids']) && is_array($data['temp_files_ids'])) {
            foreach ($data['temp_files_ids'] as $tempId) {
                if ($temp = TempFiles::find($tempId)) {
                    $old_path = $temp->file_path;
                    $new_path = str_replace('temp-files/', 'files/', $old_path);

                    if (\Storage::disk('public')->exists($old_path)) {
                        // \Storage::disk('public')->move($old_path, $new_path);
                        // Gunakan copy file temp
                        // untuk menghandle jika form terjadi error pada form request
                        \Storage::disk('public')->copy($old_path, $new_path);
                        $files = $this->files($module)->firstOrNew([
                            'module'    => $module,
                            'file_name' => $temp->file_name,
                            'file_path' => $new_path,
                            'file_size' => $temp->file_size,
                            'flag'      => $flag,
                        ]);
                        $files->save();
                        $filesIds[] = $files->id;
                    }
                    // db pada temp_files tidak dihapus dulu
                    // untuk menghandle jika terjadi error pada form request
                    // $temp->delete();
                }
            }
        }

        // Hapus sys_files dan storage yang sudah dihapus oleh user
        foreach ($this->files($module)->where('flag', $flag)->whereNotIn('id', $filesIds)->get() as $file) {
            if (\Storage::disk('public')->exists($file->file_path)) {
                \Storage::disk('public')->delete($file->file_path);;
            }
            $file->delete();
        }
    }

    public function deleteFiles($module = null, $flag = null)
    {
        // Hapus db sys_files dan storage
        $files = $this->files($module)->where('flag', $flag)->get();
        foreach ($files as $file) {
            if (\Storage::disk('public')->exists($file->file_path)) {
                \Storage::disk('public')->delete($file->file_path);;
            }
            $file->delete();
        }
    }
}
