<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;

class Category extends \TCG\Voyager\Models\Category
{
    protected $translatable = ['name'];

    public function scopeFirstLevel(Builder $query)
    {
        return $query->where('parent_id', '=', NULL);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
    /*public function posts()
    {
        return $this->belongsToMany(Voyager::modelClass('Post'))
            ->published()
            ->orderBy('created_at', 'DESC');
    }*/
}
