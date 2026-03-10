@extends('app.app_fo')

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
                                <h5>Sistem Pendukung Keputusan BPJS PBI</h5>
                                <h2>SmartPBI: Seleksi Penerima Bantuan Iuran Lebih Objektif</h2>
                                <p class="mt-3 mb-4">
                                    Sistem cerdas untuk menentukan kelayakan penerima BPJS PBI dengan metode SMART yang transparan dan akurat. Membantu pemerintah memberikan bantuan kesehatan kepada yang paling membutuhkan.
                                </p>

                                    <div>
                                        <a href="{{ route('fo.contact.index') }}" class="primary-btn">Ajukan
                                            Sekarang</a>
                                    </div>


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
                                <strong>SmartPBI</strong> adalah sistem pendukung keputusan yang membantu pemerintah menyeleksi calon penerima 
                                <em>BPJS PBI (Penerima Bantuan Iuran)</em> secara objektif, akurat, dan efisien.
                                Menggunakan metode <strong>SMART (Simple Multi-Attribute Rating Technique)</strong> untuk menghitung nilai akhir setiap alternatif berdasarkan kriteria yang telah ditentukan.
                                Kriteria yang dinilai meliputi:
                                <strong>Pekerjaan, Status Hubungan Dalam Keluarga, Data Kependudukan Sinkron, Adanya Anggota Keluarga Sudah Ditanggung Iuran BPJS, Adanya Anggota Keluarga di luar keluarga inti,</strong> dan <strong>Kependudukan Sesuai Wilayah PBI BPJS</strong>.
                            </p>

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
