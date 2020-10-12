@extends('layouts.app')

@section('content')
    <div class="container-form">
        <p class="form-title">{{ __('Register') }}</p>
            <form method="POST" action="{{ route('register') }}">
            @csrf


            <label for="name" >
                <input id="name" placeholder="{{ __('Name') }}" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                @error('name')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>


            <label for="email" >
                <input id="email" placeholder="{{ __('E-Mail Address') }}" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                @error('email')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>


            <label for="password" >
                <input id="password" placeholder="{{ __('Password') }}" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>



            <label for="password-confirm" >
                <input id="password-confirm" placeholder="{{ __('Confirm Password') }}" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </label>

            <button type="submit" class="more">
                {{ __('Register') }}
            </button>

            <a class="forgot" href="{{ route('login') }}">
                {{ __('Login') }}
            </a>

        </form>
    </div>
@endsection
