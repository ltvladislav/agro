<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Webwizo\Shortcodes\Facades\Shortcode;
use App\Shortcodes\InfoblockShortcode;
use App\Shortcodes\GalleryShortcode;

class ShortcodesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Shortcode::register('infoblock', InfoblockShortcode::class);
        Shortcode::register('gallery', GalleryShortcode::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
