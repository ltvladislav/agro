@extends('layouts.app')

@section('title', $page->title)

@section('description', '')
@section('body_class', 'default-page')
@section('wrapper_class', '')


@section('before_content')
@stop

@section('content')

    <div style="background: url({{ $page->getImage(true) }}) no-repeat center center / cover; background-attachment: fixed;" class="page-header">
        <div class="page-header-overlay"></div>
        <div class="page-header-content">
            <h1>{{ $page->getTranslatedAttribute('title') }}</h1>
        </div>
    </div>

    <div class="editor-wrap">
        <div class="editor-content">
            {!! $page->getTranslatedAttribute('body') !!}

        </div>
    </div>

@stop

@section('after_content')
@stop

@section('before_scripts')
@stop

@push('scripts')
@endpush
