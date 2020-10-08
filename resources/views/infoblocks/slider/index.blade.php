
<div class="main-slider-wrap">
    <div class="main-slider">
        @foreach($infoblock->items as $slide)
            @include('infoblocks/slider/item', [
                'slide' => $slide
            ]);
        @endforeach
    </div>

</div>
