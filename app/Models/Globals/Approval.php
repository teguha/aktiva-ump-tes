<?php

namespace App\Models\Globals;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Master\Org\Position;
use App\Models\Model;

class Approval extends Model
{
    protected $table = 'sys_approvals';

    protected $fillable = [
        'target_type',
        'target_id',
        'target',
        'module',
        'role_id',
        'user_id',
        'position_id',
        'type',
        'order',
        'status',
        'note',
    ];

    protected $dates = [
        'approved_at',
    ];

    /** MUTATOR **/
    /** ACCESSOR **/
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

    /** RELATION **/
    public function target()
    {
        return $this->morphTo();
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    /** SCOPE **/
    /** TRANSACTION **/

    /** OTHER FUNCTIONS **/
}
