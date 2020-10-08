@extends('layouts.app')


@section('title', Setting::getLocalized('site.title'))

@section('description', '')
@section('body_class', 'tvariniztvo-page')
@section('wrapper_class', '')


@section('before_content')
@stop

@section('content')
    <section class="main-section">
        <div class="table-wrap" id="table-tvarin"></div>
    </section>
@stop

@section('after_content')
@stop

@section('before_scripts')
@stop

@section('js_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.23.0/slimselect.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.23.0/slimselect.min.css" rel="stylesheet"></link>
{{--    <script src="{{mix('js/tableGenerate.js')}}"></script>--}}
@stop

@push('scripts')
@endpush
