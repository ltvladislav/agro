<?php

namespace App\Models\Request;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Request extends Model
{
    const STATUS_NEW = 'NEW';
    const STATUS_IN_WORK = 'IN_WORK';
    const STATUS_DONE = 'DONE';
    const STATUS_DENIED = 'DENIED';
    public static $statuses = [self::STATUS_NEW, self::STATUS_IN_WORK, self::STATUS_DONE, self::STATUS_DENIED];


    public function save(array $options = []) {
        if (!$this->customer_id && Auth::user()) {
            $this->customer_id = Auth::user()->getKey();
        }
        return parent::save();
    }

    public function scopeMyCustomer($query) {
        return $query->where('customer_id', Auth::user()->getKey());
    }
}
