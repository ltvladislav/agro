<header>
    <a href="/" class="logo-wrap">
        <img src="{{ asset('/image/dorad.png') }}" alt="">
    </a>
    <div class="main-header">
        @include('includes.header-socials')

        <div class="header-nav">

            <nav>
                {{ menu('main-navigation', 'menus/main-navigation') }}
            </nav>

            <div class="search-btn">
                <svg><use xlink:href="#search"></use></svg>
            </div>

            @include('includes.search')

        </div>
    </div>
</header>
