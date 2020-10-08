
<div class="news-container">
    <p class="block-title left">{{ Setting::getLocalized('posts.title') }}</p>
    <div class="news-wrap">

        @include('posts.includes.card-collection', ['posts' => App\Models\Posts\Post::getPostsForMainPage()])
    </div>

    <a href="{{ url('posts') }}" class="more">{{ Setting::getLocalized('posts.show-more-title') }}</a>
</div>


