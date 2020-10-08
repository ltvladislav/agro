
@foreach($items as $menu_item)
    <div class="footer-menu-item">
        <a href="{{ $menu_item->link() ? url($menu_item->link()) : "" }}" class="footer-menu-title" target="{{ $menu_item->target }}">
            {{ $menu_item->getTranslatedAttribute('title') }}
        </a>
        @foreach($menu_item->children as $menu_item_child)
            <a href="{{ $menu_item_child->link() ? url($menu_item_child->link()) : "#" }}" target="{{ $menu_item_child->target }}">
                {{ $menu_item_child->getTranslatedAttribute('title') }}
            </a>
        @endforeach
    </div>
@endforeach
