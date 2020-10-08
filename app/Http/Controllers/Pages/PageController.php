<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pages\Page;

class PageController extends Controller
{

    public function show($pageSlug) {
        $page = Page::findBySlug($pageSlug);
        if ($page) {
            return view('pages.show', [
                'page' => $page
            ])->withShortcodes();
        }

        if (view()->exists('pages.' . $pageSlug)) {
            return view('pages.' . $pageSlug)->withShortcodes();
        }
        return abort(404);
    }
}
