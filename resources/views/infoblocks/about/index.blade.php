
<div class="about-wrap">
    <p class="about-title">{{ $infoblock->getTranslatedAttribute('title') }}</p>
    <p class="about-subtitle">{{ strip_tags($infoblock->getTranslatedAttribute('body')) }}</p>
    <a href="{{ $infoblock->button_link ? $infoblock->button_link : '#' }}" class="more">
        {{ $infoblock->getTranslatedAttribute('button_title') }}
    </a>
</div>
