
<div class="languages">
    @php($current_locale = LaravelLocalization::getCurrentLocale())
    <a class="active-lang">{{ $current_locale }}</a>
    <div class="additional-langs">
        @foreach(LaravelLocalization::getSupportedLanguagesKeys() as $localeCode)
            @if($current_locale != $localeCode)
                <a class="" lang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode) }}">{{$localeCode}}</a>
            @endif
        @endforeach
    </div>
</div>
