<div class="additional-links-wrap">

    <div class="additional-link-item">
        <p class="additional-title">{{ $infoblock->getTranslatedAttribute('title') }}</p>
    </div>

    @foreach($infoblock->items as $item)
        @include('infoblocks/useful-links/item', [
            'item' => $item
        ])
    @endforeach

</div>
