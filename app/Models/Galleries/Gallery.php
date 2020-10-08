<?php

namespace App\Models\Galleries;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class Gallery extends Model
{
    use Translatable;

    protected $translatable = ['title', 'sub_title'];



    public function images()
    {
        return $this->hasMany(GalleryImage::class)->orderBy('order');
    }


    public static function findBySlug($slug) {
        return static::where('slug', $slug)->with('images')->first();
    }
}
