@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @php
                        $post = App\Models\Pages\Page::with('translations')->first();
                    @endphp

                    {{ app()['request']->segment(1) }}
                        <p>{{ $post->getTranslatedAttribute('title') }}</p>
                    You are logged in!

                        <script async src="https://cse.google.com/cse.js?cx=012010229175199226711:g79fumb7agq"></script>
                        <div class="gcse-search"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
