
@foreach($posts as $post)
    @include('posts.includes.post-card', ['post' => $post])
@endforeach
