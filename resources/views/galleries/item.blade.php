
<a href="{{ $image->getImage(true) }}"
   class="@if($counter == 0 || $counter == 2) small @elseif($counter == 1 || $counter == 3) big @endif"
   data-fancybox="{{ $gallery->slug }}"
   @if($counter >= 4) style="display: none" @endif>
    @if($counter < 4)
    <img src="{{ $image->getImage(true) }}" alt="{{ $image->getTranslatedAttribute('title') }}" >
    @endif
</a>
