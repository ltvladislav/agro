
<div class="three-block-item">
    <div class="three-block-icon">
        <img src="{{ $item->getImage(true) }}" alt="">
    </div>
    <p class="three-block-name">{{ $item->getTranslatedAttribute('title') }}</p>
    <p class="three-block-description"><span>{{ $item->getTranslatedAttribute('body') }}</span></p>
    <a href="{{ $item->button_link ?? "#" }}" class="more white" target="_blank">
        {{ $item->getTranslatedAttribute('button_title') ?? 'Перейти' }}
    </a>
</div>
