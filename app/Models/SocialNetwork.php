<?php

namespace App\Models;

use App\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;

class SocialNetwork extends Model
{
    use Imageable;

    protected $image_column = 'svg';
}
