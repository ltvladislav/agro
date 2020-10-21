@extends('layouts.app')

@section('title', __('auth.register'))

@section('content')
    <div class="container-form">
        <p class="form-title">{{ __('auth.register') }}</p>
            <form method="POST" action="{{ route('register') }}">
            @csrf


            <label for="name" >
                <input id="name" placeholder="{{ __('auth.user-name') }}" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                @error('name')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>


            <label for="email" >
                <input id="email" placeholder="{{ __('auth.email') }}" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                @error('email')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>


            <label for="password" >
                <input id="password" placeholder="{{ __('auth.password') }}" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>



            <label for="password-confirm" >
                <input id="password-confirm" placeholder="{{ __('auth.password-confirm') }}" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </label>

            <button type="submit" class="more">
                {{ __('auth.register-in') }}
            </button>

            <a class="forgot" href="{{ route('login') }}">
                {{ __('auth.login') }}
            </a>

        </form>
    </div>
@endsection
