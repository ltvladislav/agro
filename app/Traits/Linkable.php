<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;

trait Linkable
{
    public function getLink($column)
    {
        return $this[$column] ? $this[$column] : '#';
    }
}
