<div class="partner-slide-wrap">
    <a href="{{ $item->button_link ?? "#" }}" class="partner-item">
        <img src="{{ $item->getImage(true) }}" alt="">
    </a>
    {{--    {{ $item->getTranslatedAttribute('title') }}--}}
</div>
