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

    <div class="editor-wrap all-response">

        @foreach($requests as $request)
            <div class="request-card">
                <div class="card-heading">
                    <p class="info-title">Статус запиту: {{ __("request.STATUS_".$request->status) }}</p>
                    <p class="date">{{ $request->created_at->format('m/d/Y') }}</p>
                </div>

                <div class="card-content">
                    <div class="content">
                        @if($request->request_comment)
                            <div class="info-column">
                                <p>Коментар запиту:</p>
                                <p>{{ $request->request_comment }}</p>
                            </div>
                        @endif
                        @if($request->response_comment)
                            <div class="info-column">
                                <p>Коментар відповіді:</p>
                                <p>{{ $request->response_comment }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="files">
                        @foreach(json_decode($request->request_file) as $file)
                            <a class="request-file file" target="_blank"
                               href="{{ Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: '' }}"
                               data-file-name="{{ $file->original_name }}">

                                <span class="icon">
                                    <svg><use xlink:href="#file-response"></use></svg>
                                </span>

                                <span class="file-name">Файл запиту</span>

                            </a>
                        @endforeach


                        @if($request->response_file )

                            @foreach(json_decode($request->response_file) as $file)
                                <a class="response-file file" target="_blank"
                                   href="{{ Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: '' }}"
                                   data-file-name="{{ $file->original_name }}">

                                    <span class="icon">
                                        <svg><use xlink:href="#file"></use></svg>
                                    </span>

                                    <span class="file-name">Файл відповіді</span>

                                </a>
                            @endforeach
                        @endif

                    </div>
                </div>
            </div>


        @endforeach

            <a class="more" href="{{ route('requests-create') }}">Створити новий запит</a>
    </div>

@stop

@section('after_content')
@stop

@section('before_scripts')
@stop

@push('scripts')
@endpush
