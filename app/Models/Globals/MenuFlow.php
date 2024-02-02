<?php

namespace App\Models\Globals;

use App\Models\Auth\Role;
use App\Models\Model;

class MenuFlow extends Model
{
    protected $table = 'sys_menu_flows';
    protected $fillable = [
        'menu_id', 
        'role_id', 
        'type',
        'order',
    ];

    public function getShowTypeAttribute()
    {
        if ($this->type == 2) {
            return 'Paralel';
        }
        return 'Sekuensial';
    }

    public function getShowColorAttribute()
    {
        if ($this->type == 2) {
            return 'info';
        }
        return 'primary';
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function scopeHasModule($query, $module)
    {
        return $query->whereHas('menu', function ($q) use ($module) {
            $q->where('module', $module);
        });
    }
}
