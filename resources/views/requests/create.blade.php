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
            <h1>Створити запит</h1>
        </div>
    </div>

    <div class="container-form create-request">
        <form method="post" action="{{ route('requests.store') }}" enctype="multipart/form-data">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <label for="">
                <textarea placeholder="Коментар для запиту" type="text" name="comment"></textarea>
            </label>
            <label for="">
                <input type="file" multiple name="file[]">
            </label>
            <button class="more" type="submit">Загрузить</button>
        </form>

    </div>

@stop

@section('after_content')
@stop

@section('before_scripts')
@stop

@push('scripts')
@endpush
