<?php

namespace App\Models\Pages;

use App\Traits\Imageable;
use App\Traits\Linkable;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\Translatable;

class Page extends \TCG\Voyager\Models\Page
{
    use Imageable;

    protected $image_column = 'image';
    protected $translatable = ['title', 'excerpt', 'body', 'meta_description', 'meta_keywords'];


    public static function findBySlug($slug) {
        return static::where('slug', $slug)->first();
    }

    public function getImage($storage = false)
    {
        if ($this->image) {
            return Imageable::getImageByColumnValue($this->image, $storage);
        }
        else {
            return Imageable::getImageByColumnValue(setting('pages.default-page-image'), $storage);
        }
    }
}
