<?php

namespace App\Models\Galleries;

use App\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class GalleryImage extends Model
{
    use Translatable,
        Imageable;

    protected $image_column = 'image';

    protected $guarded = [];

    protected $translatable = ['caption'];



    /**
     * Return the Highest Order Infoblock Item.
     *
     * @return number Order number
     */
    public static function highestOrderInfoblockItem()
    {
        $order = 1;

        $item = static::orderBy('order', 'DESC')
            ->first();

        if (!is_null($item)) {
            $order = intval($item->order) + 1;
        }

        return $order;
    }
}
