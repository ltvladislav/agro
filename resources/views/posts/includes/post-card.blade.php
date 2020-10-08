
<div class="news-card">
    <a href="{{ route('post', $post->slug) }}" class="news-image">
        <img src="{{ $post->getImage(true) }}" alt="">
    </a>
    <div class="new-card-content">
        <div class="new-additional-info">
            <div class="new-additional-item">
                <div class="news-info-icon">
                    <img src="{{ asset('/image/calendar.svg') }}" alt="">
                </div>
                <p>{{ $post->created_at->format('d.m.y') }}</p>
            </div>
        </div>
        <a href="{{ route('post', $post->slug) }}" class="new-name">{{ $post->getTranslatedAttribute('title') }}</a>

        <p class="new-description">{!! $post->getTranslatedAttribute('excerpt') !!}</p>
        <a href="{{ route('post', $post->slug) }}" class="more">@lang('main.read-more')</a>
    </div>
</div>
