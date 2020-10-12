<header>
    <a href="/" class="logo-wrap">
        <img src="{{ asset('/image/agrologo.png') }}" alt="">
    </a>
    <div class="main-header">
        <div class="header-nav">

            <nav>
                {{ menu('main-navigation', 'menus/main-navigation') }}
            </nav>

            @include('includes.languages')
        </div>
    </div>
</header>
