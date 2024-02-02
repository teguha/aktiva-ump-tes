<?php

namespace App\Models\Traits;

trait Utilities
{
    public function canDeleted()
    {
        return true;
    }

    public function labelStatus($status = null)
    {
        return \Base::getStatus($status ?? $this->status);
    }

    public function labelVersion()
    {
        $colors = [0 => 'primary','info','success','warning','danger'];
        $label = $this->version;
        $color = $colors[$this->version] ?? 'dark';
        return \Base::makeLabel($label, $color);
    }
}
