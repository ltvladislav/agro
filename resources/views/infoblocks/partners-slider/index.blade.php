
<div class="partners-wrap">
    <p class="block-title left">{{ $infoblock->getTranslatedAttribute('title') }}</p>
    <div class="partners-slider">

        @foreach($infoblock->items as $item)
            @include('infoblocks/partners-slider/item', [
                'item' => $item
            ])
        @endforeach

    </div>
</div>
