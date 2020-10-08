@extends('layouts.app')

@section('title', $post->title)

@section('body_class', 'post-show')

@section('content-title', $post->title)

@section('content')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div style="background: url({{ Storage::disk(config('voyager.storage.disk'))->url(str_replace('\\', '/', $post->image)) }}) no-repeat center center / cover; background-attachment: fixed;" class="page-header">
        <div class="page-header-overlay"></div>
    </div>

    <div class="container-main">
        <div class="editor-wrap">
            <div class="page-heading">
                <h1>{{ $post->title }}</h1>
            </div>

            <div class="editor-content">
                {!! $post->body !!}
            </div>
            <div class="post-date">
                <p>{{ $post->created_at->format('d-m-Y') }}</p>
            </div>
        </div>


    </div>

    @include('posts.preview')


@endsection
