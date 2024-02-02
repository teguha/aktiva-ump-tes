<?php

namespace App\Models\Globals;

use App\Models\Model;

class TempFiles extends Model
{
	protected $table = 'temp_files';

	protected $fillable = [
		'file_name',
		'file_path',
        'file_size',
		'flag', //Tambahkan flag jika ada kondisi tertentu
	];
    
    protected $appends = [
        'file_url',
        'file_type',
    ];

    public function target()
    {
        return $this->morphTo();
    }

    public function getFileUrlAttribute()
    {
    	if ($this->file_path) {
    		// Handle jika ada yg menyimpan full url
    		$file_path = explode('/storage/', $this->file_path);
    		$file_path = end($file_path);
        	return url('storage/'.$file_path);
    	}
    	return null;
    }

    public function getFileTypeAttribute()
    {
        if ($this->file_path) {
            $extension = explode('.', $this->file_path);
            return strtolower(end($extension));
        }
        return null;
    }

    public static function getFileById($tempId = null)
    {
        if ($file = static::find($tempId)) {
            if (\Storage::disk('public')->exists($file->file_path)) {
                return \Storage::disk('public')->path($file->file_path);
            }
        }
        return null;
    }
}
