
<a href="{{ $item->button_link ?? "#" }}" class="additional-link-item">
    <div class="additional-link-icon">
        <img src="{{ $item->getImage(true) }}" alt="">
    </div>
    <div class="additional-content">
        <p class="additional-name">{{ $item->getTranslatedAttribute('title') }}</p>
        <p class="additional-subtitle">{{ $item->getTranslatedAttribute('body') }}</p>
    </div>
</a>

