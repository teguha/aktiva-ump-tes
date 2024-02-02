<?php

namespace App\Models;

use App\Models\Traits\RaidModel;
use App\Models\Traits\ResponseTrait;
use App\Models\Traits\Utilities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as Base;

class Model extends Base
{
    use HasFactory;
    use RaidModel, Utilities, ResponseTrait;
}
