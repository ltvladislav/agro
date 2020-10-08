<div class="gallery-wrap">

    <p class="block-title left">{{ $gallery->getTranslatedAttribute('title') }}</p>

    <div class="gallery">
        @php($counter = 0)

        @foreach($gallery->images as $image)
            @include('galleries/item', [
                'image' => $image,
                'counter' => $counter++
            ])
        @endforeach

    </div>

</div>
