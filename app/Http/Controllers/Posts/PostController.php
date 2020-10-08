<?php

namespace App\Http\Controllers\Posts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Posts\Category;
use App\Models\Posts\Post;
use Illuminate\View\View;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Facades\Voyager;

class PostController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    public function update(Request $request, $id)
    {
        $result = parent::update($request, $id);

        $post = Post::find($id);
        $this->syncCategories($post, $request->input('categories'));

        return $result;
    }
    public function store(Request $request)
    {
        $result = parent::store($request);

        $slug = $this->getSlug($request);
        $post = Post::where('slug', '=', $slug)->first();
        $this->syncCategories($post, $request->input('categories'));

        return $result;
    }
    private function syncCategories($post, $categories) {
        if ($categories) {
            $post->categories()->sync($categories);
        }
    }



    const PAGINATE_COUNT = 6;

    public function publicShow($slug) {
        return view('posts.show', [
            'post' => Post::whereSlug($slug)->firstOrFail()
        ]);
    }
    public function publicIndex() {
        $categories = Category::all();

        return view('posts.index', [
            'categories' => $categories,
            'posts' => Post::last(static::PAGINATE_COUNT)->get()
        ]);
    }


    public function postService(Request $request) {
        if ($request["methodName"] == "getPostByCategory") {
            $posts = static::getPosts($request['data']);

            if (count($posts) > 0) {
                $viewResult = view('posts.includes.card-collection', [
                    'posts' => $posts
                ])->render();
                $existsMore = static:: existsMorePost($request['data']  );
            }
            else {
                $viewResult = '';
                $existsMore = false;
            }
            $response = response()->json([
                'view' => $viewResult,
                'existsMore' => $existsMore
            ]);
        }
        else {
            $response = response()->json(array(), 404);
        }

        return $response;
    }


    private static function getPosts($config) {
        $categoryId = $config['categoryId'];
        $skip = $config['pageNumber'] * static::PAGINATE_COUNT;
        return $categoryId == 0 ?
            Post::last()->skip($skip)->take(static::PAGINATE_COUNT)->get() :
            Post::getByCategory($categoryId, $skip, static::PAGINATE_COUNT);
    }
    private static function existsMorePost($config) {
        $categoryId = $config['categoryId'];
        $skip = ($config['pageNumber'] + 1) * static::PAGINATE_COUNT;
        return count($categoryId == 0 ?
            Post::last()->skip($skip)->take(1)->get() :
            Post::getByCategory($categoryId, $skip, 1)) > 0;
    }
}
