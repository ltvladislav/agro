@extends('layouts.app')

@section('content')

    <div class="container-form">
        <p class="form-title">{{ __('Login') }}</p>
        <form method="POST" action="{{ route('login') }}">
            @csrf


            <label for="email">
                <input id="email" type="email" placeholder="{{ __('E-Mail Address') }}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </label>


            <label for="password" >
                <input id="password" placeholder="{{ __('Password') }}" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </label>


            <div class="custom-check ">
                <label>
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span class="square-check"></span>
                    <p>{{ __('Remember Me') }}</p>
                </label>
            </div>

            <button type="submit" class="more">
                {{ __('Login') }}
            </button>

            <a class="forgot" href="{{ route('register') }}">
                {{ __('Register') }}
            </a>

            @if (Route::has('password.request'))
                <a class="forgot" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif

        </form>
    </div>


@endsection
