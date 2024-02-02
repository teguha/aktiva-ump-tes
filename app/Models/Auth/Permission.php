<?php

namespace App\Models\Auth;

use App\Models\Traits\RaidModel;
use App\Models\Traits\ResponseTrait;
use App\Models\Traits\Utilities;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use RaidModel, Utilities, ResponseTrait;
}
