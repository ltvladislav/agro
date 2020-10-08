
<div class="slide-wrap">
    <div class="overlay"></div>
    <img src="{{ $slide->getImage(true) }}" alt="">
    <div class="slide-content">
        <p class="slide-title">{{ $slide->getTranslatedAttribute('title') }}</p>
        <p class="slide-subtitle">{{ $slide->getTranslatedAttribute('body') }}</p>
        @if($slide->button_link)
            <a href="{{ $slide->button_link }}" class="more">
                {{ $slide->getTranslatedAttribute('button_title') ?? 'Дізнатись більше' }}
            </a>
        @endif
    </div>
</div>
