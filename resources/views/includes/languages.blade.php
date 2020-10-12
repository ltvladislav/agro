
<div class="languages">
    <div class="header-login">
        <a href="@guest{{ route('login') }}@else{{ '/home' }}@endguest">
            <svg> <use xlink:href="#user"> </use></svg>
        </a>
    </div>

    @php($current_locale = LaravelLocalization::getCurrentLocale())
{{--    <a class="active-lang">{{ $current_locale }}</a>--}}
    <div class="additional-langs">
        @foreach(LaravelLocalization::getSupportedLanguagesKeys() as $localeCode)
            @if($current_locale != $localeCode)
                <a class="" lang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode) }}">{{$localeCode}}</a>
            @endif
        @endforeach
    </div>
</div>
