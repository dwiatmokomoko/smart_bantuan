@extends('app.app_fo')

@push('styles')
    <style>
        .hero-section {
            position: relative;
            overflow: hidden;
        }

        .hero__item {
            height: 100vh;
            /* 100% tinggi layar */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
        }

        .hero__text {
            color: #fff;
        }

        .badge-criteria {
            display: inline-block;
            padding: .4rem .75rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .3);
            margin: .25rem;
            font-size: .9rem;
            backdrop-filter: blur(2px);
        }
    </style>
@endpush

@section('content_fo')
    <!-- Hero Section Begin -->
    <section class="hero-section">
        <div class="hero__slider owl-carousel">
            <!-- Slide 1 -->
            <div class="hero__item set-bg"
                data-setbg="{{ asset('fo/img/rusun.jpeg') }}">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="hero__text">
                                <h5>Bersama Wujudkan Hunian Layak untuk Semua</h5>
                                <h2>Seleksi Penerima Rusunawa Lebih Objektif dengan ROC & SMART</h2>
                                <p class="mt-3 mb-4">
                                    Bobot kriteria ditentukan dengan <strong>Rank Order Centroid (ROC)</strong>, penilaian
                                    alternatif memakai
                                    <strong>SMART</strong> agar rekomendasi lebih transparan dan tepat sasaran.
                                </p>
                                @auth('web')
                                    <div class="{{ request()->is('pra-kelayakan*') ? 'active' : '' }}">
                                        <a href="{{ route('pre-eligibility.form') }}" class="primary-btn">Cek Kelayakan
                                            Sekarang</a>
                                    </div>
                                @else
                                    <div>
                                        <a href="{{ route('user.login') }}" class="primary-btn">Cek Kelayakan
                                            Sekarang</a>
                                    </div>
                                @endauth

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            {{-- <div class="hero__item set-bg" data-setbg="{{ asset('fo/img/rusun.jpeg') }}
            {{-- https://dsp.uii.ac.id/wp-content/uploads/2024/05/Rusunawa-Putri-httpsmaps.app_.goo_.gl7wBzED4Apux3CfLe7-2.png --}}
            {{-- ">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="hero__text">
                                <h5>Tepat Sasaran, Transparan, dan Berbasis Data</h5>
                                <h2>Sistem Pendukung Keputusan Rusunawa Berbasis SMART</h2>
                                <p class="mt-3 mb-4">
                                    Input data kriteria & alternatif, hitung bobot otomatis via ROC, lalu
                                    lakukan pemeringkatan SMART untuk mendapatkan rekomendasi penerima.
                                </p>
                                @auth('web')
                                    <div class="{{ request()->is('pra-kelayakan*') ? 'active' : '' }}">
                                        <a href="{{ route('pre-eligibility.form') }}" class="primary-btn">Mulai Penilaian</a>
                                    </div>
                                @else
                                    <div>
                                        <a href="{{ route('user.login') }}" class="primary-btn">Mulai Penilaian</a>
                                    </div>
                                @endauth

                            </div>
                        </div>
                    </div>
                </div>
            </div>  --}}
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- About Us Section Begin -->
    <section class="about-us spad">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-9">
                    <div class="about__text">
                        <div class="section-title text-center">
                            <h3>Tentang Aplikasi</h3>
                        </div>
                        <div class="about__content text-center">
                            <p>
                                Aplikasi ini membantu Pemerintah Kota Yogyakarta menyeleksi calon penerima
                                <em>Rumah Susun Sederhana Sewa (Rusunawa)</em> secara objektif, akurat, dan efisien.
                                <strong>ROC</strong> digunakan untuk menentukan bobot kriteria berdasarkan urutan prioritas,
                                sedangkan <strong>SMART</strong> dipakai untuk menghitung nilai akhir setiap alternatif.
                                Kriteria yang dinilai meliputi:
                                <strong>penghasilan, pekerjaan, status penempatan, perkawinan,</strong> dan <strong>calon
                                    penghuni</strong>.
                            </p>

                            {{-- <div class="row mt-4 text-start">
                                <div class="col-md-6">
                                    <ul>
                                        <li>Input data kriteria & alternatif</li>
                                        <li>Penentuan bobot otomatis (ROC)</li>
                                        <li>Normalisasi & skoring (SMART)</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul>
                                        <li>Hasil perankingan yang transparan</li>
                                        <li>Rekomendasi untuk pengambil keputusan</li>
                                        <li>Ekspor & dokumentasi hasil</li>
                                    </ul>
                                </div>
                            </div> --}}

                            <div class="mt-5">
                                <a href="{{ route('fo.about.index') }}" class="primary-btn">Pelajari Metode</a>
                                @if (Route::has('ranking.index'))
                                    <a href="{{ route('ranking.index') }}" class="primary-btn"
                                        style="margin-left:12px;">Lihat Hasil Perankingan</a>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Us Section End -->
@endsection
