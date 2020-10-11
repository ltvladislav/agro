@extends('layouts.app')

@section('title', 'Запити')

@section('description', '')
@section('body_class', 'default-page')
@section('wrapper_class', '')


@section('before_content')
@stop

@section('content')

    <div style="background: url({{ '' }}) no-repeat center center / cover; background-attachment: fixed;" class="page-header">
        <div class="page-header-overlay"></div>
        <div class="page-header-content">
            <h1>Запити</h1>
        </div>
    </div>

    <div class="editor-wrap">
        <div class="editor-content">

            @foreach($requests as $request)
                @foreach(json_decode($request->request_file) as $file)
                    <a class="fileType" target="_blank"
                       href="{{ Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: '' }}"
                       data-file-name="{{ $file->original_name }}">
                        {{ $file->original_name ?: '' }}
                    </a>
                @endforeach
                {{ $request->id . ' - ' . $request->status . ' - ' . $request->customer_id . ' - ' .
                 $request->performer_id . ' - ' . $request->response_file . ' - ' . $request->request_comment . ' - '}}
            @endforeach
        </div>
    </div>

@stop

@section('after_content')
@stop

@section('before_scripts')
@stop

@push('scripts')
@endpush
