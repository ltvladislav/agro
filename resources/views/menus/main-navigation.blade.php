
@foreach($items as $menu_item)
    <div class="nav-item">
        <a class="nav-link" href="{{ $menu_item->link() ? url($menu_item->link()) : "#" }}">
            <span>{{ $menu_item->getTranslatedAttribute('title') }}</span>
        </a>

        @if (count($menu_item->children) > 0)
            <div class="sub-nav">
            @foreach($menu_item->children as $menu_item_child)
                <a href="{{ $menu_item_child->link() ? url($menu_item_child->link()) : "#" }}" class="sub-nav-item">
                    {{ $menu_item_child->getTranslatedAttribute('title') }}
                </a>
            @endforeach
            </div>
        @endif
    </div>
@endforeach
