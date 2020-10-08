@extends('layouts.app')

@section('title', Setting::getLocalized('posts.title'))

@section('content-title', 'Posts')

@section('content')

    <div class="container-main">
        <h1>{{ Setting::getLocalized('posts.title') }}</h1>

        <div class="news-category">
            <div class="category-list">
                <a class="category-item"
                   data-category-id="0"
                   data-category-slug="">
                    @lang('main.all')
                </a>

                @foreach($categories as $category)
                    <a class="category-item"
                       data-category-id="{{ $category->id }}"
                       data-category-slug="{{ $category->slug }}">
                        {{$category->getTranslatedAttribute('name') }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="news-wrapper" data-paginate="posts">
            @include('posts.includes.card-collection', ['posts' => $posts])
        </div>
        @include('includes.pagination')
    </div>

@endsection
