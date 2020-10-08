<?php

namespace App\Shortcodes;

use App\Models\Infoblocks\Infoblock;

class InfoblockShortcode
{
    public function register($shortcode, $content, $compiler, $name, $viewData)
    {
        $infoblock = Infoblock::findBySlug($shortcode->code);

        if (!$infoblock) {
            return '';
        }

        if ($shortcode->view) {
            $viewName = $shortcode->view;
        }
        else {
            $viewName = $infoblock->type->blade_path;
        }

        return view('infoblocks/' . $viewName . '/index', [
            'infoblock' => $infoblock
        ]);
    }
}
