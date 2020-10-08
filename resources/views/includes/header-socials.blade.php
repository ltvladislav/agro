<div class="header-socials">
    <div class="header-socials-list">
        @foreach(App\Models\SocialNetwork::all() as $network)
            <a href="{{ $network->link }}" target="_blank">
                <img src="{{ $network->getImage(true) }}" alt="">
            </a>
        @endforeach

        @foreach(explode(',', setting('contacts.email')) as $email)
            <a type="mail" href="mailto:{{ $email }}">
                <img src="{{ asset('/image/mail.svg') }}" alt="">
                <span>{{ $email }}</span>
            </a>
        @endforeach

        @foreach(explode(',', setting('contacts.mobile-phone')) as $phone)
            <a type="phone" href="tel^{{ $phone }}">
                <img src="{{ asset('/image/phone.svg') }}" alt="">
                <span>{{ $phone }}</span>
            </a>
        @endforeach
    </div>

    <div class="header-login">
        <a href="#">
            <svg> <use xlink:href="#user"> </use></svg>
        </a>
    </div>

    @include('includes.languages')
</div>
