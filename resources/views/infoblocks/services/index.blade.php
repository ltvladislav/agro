<div class="three-block-wrap">
    @foreach($infoblock->items as $item)
        @include('infoblocks/services/item', [
            'item' => $item
        ])
    @endforeach
</div>
