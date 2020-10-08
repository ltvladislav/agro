<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;

trait Imageable
{


    /**
     * Get full path to image (from image or file type)
     *
     * @param boolean   $storage
     * @return string
     */
    public function getImage($storage = false)
    {
        return static::getImageByColumnValue($this[$this->image_column], $storage);
    }


    public static function getImageByColumnValue($image, $storage = false)
    {
        $path = '';
        try {
            $file = json_decode($image);
            if (!empty($file)) {
                $path = $file[0]->download_link;
            }
            else {
                if ($image != '[]') {
                    $path = $image;
                }
            }
        }
        catch (\Exception $exception) {

        }

        if ($storage && !empty($path)) {
            return Voyager::image($path);
        }
        return $path;
    }

}
