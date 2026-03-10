@extends('app.app_fo')

@section('content_fo')
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__option">
                        <a href="{{ route('fo.home.index') }}"><span class="fa fa-home"></span> Home</a>
                        <span>Tentang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Blog Details Section Begin -->
    <section class="blog-details-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h3>Tentang Sistem Seleksi Penerima Rusunawa (ROC + SMART)</h3>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <div class="blog__details__text">
                        <div class="blog__details__title">
                            <p>
                                Ketersediaan hunian layak merupakan kebutuhan dasar. Di Kota Yogyakarta, dinamika urbanisasi
                                menekan ketersediaan lahan dan hunian, khususnya bagi masyarakat berpenghasilan rendah (MBR).
                            </p>
                            <p>
                                Pemerintah menyediakan Rumah Susun Sederhana Sewa (Rusunawa) untuk membantu warga yang belum
                                memiliki hunian tetap. Agar seleksi calon penghuni lebih objektif dan efisien, kami
                                mengembangkan sistem pendukung keputusan berbasis <strong>Rank Order Centroid (ROC)</strong> dan
                                <strong>Simple Multi Attribute Rating Technique (SMART)</strong>.
                            </p>
                        </div>

                        <div class="blog__details__quote">
                            <p>
                                “Bobot kriteria ditentukan secara <em>fair</em> dengan ROC, penilaian alternatif dihitung transparan
                                menggunakan SMART — hasil akhirnya berupa perankingan yang mudah diaudit.”
                            </p>
                        </div>

                        <div class="blog__details__title__more">
                            <h4>Latar Belakang</h4>
                            <p>
                                Tantangan utama seleksi penerima Rusunawa adalah konsistensi penilaian dan keterlacakan
                                keputusan. Meski <em>regulasi teknis</em> telah mengarahkan indikator kelayakan, proses manual
                                rentan subjektif dan memakan waktu. Diperlukan pendekatan terstruktur agar keputusan tepat
                                sasaran, adil, dan dapat dipertanggungjawabkan.
                            </p>
                        </div>

                        <div class="blog__details__title__more">
                            <h4>Tujuan Sistem</h4>
                            <ul class="pl-5 pb-5 pt-3">
                                <li>Menyeleksi calon penghuni Rusunawa secara objektif berbasis data.</li>
                                <li>Mengurangi ketidaktepatan sasaran serta mempercepat proses verifikasi.</li>
                                <li>Menyediakan perankingan transparan sebagai rekomendasi bagi pengambil keputusan.</li>
                            </ul>
                        </div>

                        <div class="blog__details__title__more">
                            <h4>Metodologi</h4>
                            <p>
                                <strong>1) Penentuan Bobot dengan ROC.</strong> Pengambil keputusan mengurutkan kriteria dari
                                paling penting hingga kurang penting. Bobot setiap kriteria dihitung otomatis dengan rumus ROC.
                                Pendekatan ini sederhana, konsisten, dan cocok saat hanya tersedia urutan prioritas.
                            </p>
                            <p>
                                <strong>2) Penilaian Alternatif dengan SMART.</strong> Setiap kriteria dibentuk fungsi nilai (utility),
                                dilakukan normalisasi (benefit/cost), lalu dihitung skor akhir sebagai penjumlahan tertimbang:
                                <em>skor alternatif = Σ (bobot ROC × nilai SMART)</em>. Hasilnya berupa peringkat calon penghuni.
                            </p>
                            <p class="mb-0"><strong>Kriteria yang digunakan:</strong></p>
                            <ul class="pl-5 pt-2">
                                <li>Pekerjaan</li>
                                <li>Status Hubungan Dalam Keluarga</li>
                                <li>Data Kependudukan Sinkron</li>
                                <li>Adanya Anggota Keluarga Sudah Ditanggung Iuran BPJS</li>
                                <li>Adanya Anggota Keluarga di luar keluarga inti</li>
                                <li>Kependudukan Sesuai Wilayah PBI BPJS</li>
                            </ul>
                        </div>

                        <div class="blog__details__title__more">
                            <h4>Manfaat Sistem</h4>
                            <ul class="pl-5 pb-5 pt-3">
                                <li>Rekomendasi penerima yang akurat, adil, dan mudah diaudit.</li>
                                <li>Proses seleksi lebih cepat dan terdokumentasi.</li>
                                <li>Meningkatkan transparansi dan akuntabilitas penyaluran fasilitas hunian.</li>
                            </ul>
                        </div>

                        <div class="blog__details__item">
                            <div class="blog__details__item__pic">
                                <img src="{{ asset('fo/img/rusun.jpeg') }}" alt="Ilustrasi Rusunawa">
                            </div>
                        </div>

                        <div class="blog__details__item">
                            <div class="blog__details__item__text">
                                <p>
                                    Dengan pendekatan ROC + SMART, proses seleksi calon penghuni Rusunawa menjadi lebih
                                    terstruktur, terukur, dan berkeadilan—mendukung misi menyediakan hunian layak bagi
                                    masyarakat yang benar-benar membutuhkan.
                                </p>
                            </div>
                        </div>

                        <div class="blog__details__desc pt-5">
                            <h4>Penutup</h4>
                            <p>
                                Kami berharap sistem ini menjadi referensi yang kuat dalam pengambilan keputusan penerima
                                Rusunawa. Saran dan masukan sangat kami nantikan demi penyempurnaan berkelanjutan.
                            </p>
                            <p>
                                Untuk pertanyaan lebih lanjut atau kolaborasi, silakan kunjungi halaman
                                <a href="{{ route('fo.ourteam.index') }}">kontak</a> kami.
                            </p>
                            <p>
                                Detail informasi Rusunawa bisa diklik
                                <a href="https://drive.google.com/file/d/1jJ3dnEG75FBmIhcRr_a3wF0h_Dw7Tym0/view?usp=sharing">di sini.</a>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Section End -->
@endsection
