<?php

namespace App\Models\Globals;

use App\Models\Auth\User;
use App\Models\Model;
use Carbon\Carbon;

class Activity extends Model
{
    protected $table = 'sys_activities';

    protected $guarded = [];

    protected $appends = [
        'show_module',
        'show_message',
    ];

    /** ACCESSOR **/
    public function getShowModuleAttribute()
    {
        $modules = \Base::getModules();
        return $modules[$this->module] ?? '[System]';
    }

    public function getShowMessageAttribute()
    {
        return $this->message;
    }


    /** RELATION **/
    public function target()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** SCOPE **/
    public function scopeGrid($query)
    {
        return $query->latest();
    }

    public function scopeFilters($query)
    {
        return $query->filterBy('message')
                    ->when($module = request()->post('module_name'), function ($q) use ($module) {
                        $q->where('module', 'LIKE', '%'.$module.'%');
                    })
                    ->when($created_by = request()->post('created_by'), function ($q) use ($created_by) {
                        $q->where('created_by', $created_by);
                    })
                    ->when($date_start = request()->post('date_start'), function ($q) use ($date_start) {
                        $date_start = Carbon::createFromFormat('d/m/Y', $date_start);
                        $q->where('created_at', '>=', $date_start);
                    })
                    ->when($date_end = request()->post('date_end'), function ($q) use ($date_end) {
                        $date_end = Carbon::createFromFormat('d/m/Y', $date_end);
                        $q->where('created_at', '<=', $date_end);
                    });
    }
}
