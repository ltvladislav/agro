@extends('layouts.app')

@section('content')

    <div class="home-menu">
        <div class="wrap">
            <a href="{{ route('requests') }}">Історія запитів</a>
            <a href="{{ route('requests-create') }}">Створити новий запит</a>
        </div>


        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit">
                <span>Вихід</span>
                <span class="icon"><svg><use xlink:href="#exit"></use></svg></span>
            </button>
        </form>
    </div>
    <div class="container-main">
        <p class="block-title left">Особистий кабінет</p>
        <div class="info-block">
            <p class="info-title">Персональна інформація</p>
            <div class="info-line">
                <p class="info-name">Ім'я:</p>
                <p class="info-name"> {{ Auth::user()->name }} </p>
            </div>
            <div class="info-line">
                <p class="info-name">E-mail:</p>
                <p class="info-name">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>


@endsection
