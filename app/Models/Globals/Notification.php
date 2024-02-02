<?php

namespace App\Models\Globals;

use App\Models\Auth\User;
use App\Models\Model;

class Notification extends Model
{
    protected $table = 'sys_notifications';

    protected $guarded = [];

    protected $appends = [
        'show_module',
        'show_message',
        'full_url',
        'from_name',
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

    public function getFullUrlAttribute()
    {
        return $this->url;
    }

    public function getFromNameAttribute()
    {
        return $this->creatorName(false);
    }

    /** RELATION **/
    public function target()
    {
        return $this->morphTo();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'sys_notifications_users', 'notification_id', 'user_id')
                    ->withPivot('readed_at');
    }
}
