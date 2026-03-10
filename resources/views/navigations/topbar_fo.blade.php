<header class="header-section header-normal">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="header__logo">
                    <a href="{{ route('fo.home.index') }}"><img src="{{ asset('fo/img/silayak-logo2.png') }}" alt="Logo SiLayak" class="rounded-circle" style="height: 80px; width: 80px;"></a>
                </div>
            </div>
            <div class="col-lg-9 col-md-9">
                <nav class="header__menu">
                    <ul>
                        <li class="{{ request()->is('/') ? 'active' : '' }}">
                            <a href="{{ route('fo.home.index') }}">Beranda</a>
                        </li>
                        <li class="{{ request()->is('about') ? 'active' : '' }}">
                            <a href="{{ route('fo.about.index') }}">Tentang</a>
                        </li>
                        @auth('web')
                            @php
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
                            <li>
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
                                <a href="#" class="nav-link text-white" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    LOGOUT {{ strtok(auth('web')->user()->name, ' ') }}
                                </a>
                                <form id="logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </nav>
                @auth('web')
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const trigger = document.getElementById('logout-trigger');
                            if (trigger) {
                                trigger.addEventListener('click', function() {
                                    if (confirm('Anda sudah login. Apakah Anda ingin logout?')) {
                                        document.getElementById('logout-form').submit();
                                    }
                                });
                            }
                        });
                    </script>
                @endauth
            </div>
            <div class="canvas__open">
                <span class="fa fa-bars"></span>
            </div>
        </div>
    </div>
</header>
