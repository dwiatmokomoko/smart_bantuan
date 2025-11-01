<div class="offcanvas__menu__wrapper">
    <div class="canvas__close">
        <span class="fa fa-times-circle-o"></span>
    </div>
    <div class="offcanvas__logo">
        <a href="#"><img src="{{ asset('fo/img/silayak-logo.png') }}" alt=""></a>
    </div>

    
    <nav class="offcanvas__menu mobile-menu">
        <ul>
            <li class="{{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ route('fo.home.index') }}">Beranda</a>
            </li>

            <li class="{{ request()->is('about') ? 'active' : '' }}">
                <a href="{{ route('fo.about.index') }}">Tentang</a>
            </li>

            @auth('web')
                @php
                    // dianggap “sudah mengajukan” jika ada minimal satu paket berkas
                    $hasPengajuan = \App\Models\UserBerkas::where('user_id', auth('web')->id())->exists();
                @endphp

                @if ($hasPengajuan)
                    <li class="{{ request()->is('pengajuan/riwayat') ? 'active' : '' }}">
                        <a href="{{ route('fo.pengajuan.history') }}">Riwayat</a>
                    </li>
                @else
                    <li class="{{ request()->is('count') ? 'active' : '' }}">
                        <a href="{{ route('fo.count.index') }}">Pengajuan</a>
                    </li>
                @endif
            @else
                <li class="{{ request()->is('register') ? 'active' : '' }}">
                    <a href="{{ route('pre-eligibility.form') }}">Register</a>
                </li>
            @endauth


            <li class="{{ request()->is('contact') ? 'active' : '' }}">
                <a href="{{ route('fo.contact.index') }}">Petunjuk</a>
            </li>

            <li class="{{ request()->is('ourteam') ? 'active' : '' }}">
                <a href="{{ route('fo.ourteam.index') }}">Pengembang</a>
            </li>

            {{-- Login/Logout --}}
            @guest('web')
                <li class="{{ request()->is('user/login') ? 'active' : '' }}">
                    <a href="{{ route('user.login') }}" class="nav-link text-white">LOGIN</a>
                </li>
            @else
                <li class="{{ request()->is('user/logout') ? 'active' : '' }}">
                    <a href="#" class="nav-link text-white"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        LOGOUT {{ strtok(auth('web')->user()->name, ' ') }}
                    </a>
                    <form id="logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            @endguest


        </ul>
    </nav>
    <div id="mobile-menu-wrap"></div>
</div>
