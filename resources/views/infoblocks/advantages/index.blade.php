<div class="advantages-wrap">
    <p class="block-title ">{{ $infoblock->getTranslatedAttribute('title') }}</p>
    <div class="advantages-list">
        @foreach($infoblock->items as $item)
            @include('infoblocks/advantages/item', [
                'item' => $item
            ])
        @endforeach
    </div>

</div>
