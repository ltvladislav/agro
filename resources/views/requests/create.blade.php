@extends('layouts.app')

@section('title', 'Запит - створити')

@section('description', '')
@section('body_class', 'default-page')
@section('wrapper_class', '')


@section('before_content')
@stop

@section('content')

    <div style="background: url({{ '' }}) no-repeat center center / cover; background-attachment: fixed;" class="page-header">
        <div class="page-header-overlay"></div>
        <div class="page-header-content">
            <h1>Запит - створити</h1>
        </div>
    </div>

    <div class="editor-wrap">
        <div class="editor-content">
            <form method="post" action="{{ route('requests.store') }}" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <textarea type="text" name="comment"></textarea>
                <input type="file" multiple name="file[]">
                <button type="submit">Загрузить</button>
            </form>
        </div>
    </div>

@stop

@section('after_content')
@stop

@section('before_scripts')
@stop

@push('scripts')
@endpush
