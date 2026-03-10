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
                        <h3>Tentang SmartPBI: Sistem Seleksi Penerima BPJS PBI</h3>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <div class="blog__details__text">
                        <div class="blog__details__title">
                            <p>
                                <strong>BPJS PBI (Penerima Bantuan Iuran)</strong> adalah program jaminan kesehatan gratis dari pemerintah bagi warga miskin dan rentan miskin. Iuran bulanannya dibayar penuh melalui APBN/APBD. Peserta PBI berhak mendapatkan layanan kesehatan kelas 3 di fasilitas kesehatan tingkat pertama hingga rumah sakit rujukan.
                            </p>
                            <p>
                                Pendaftaran BPJS PBI harus terdaftar dalam <strong>Data Terpadu Kesejahteraan Sosial (DTKS)</strong> yang dikelola Kementerian Sosial. Untuk memastikan seleksi penerima yang objektif, akurat, dan transparan, kami mengembangkan sistem pendukung keputusan berbasis <strong>Simple Multi Attribute Rating Technique (SMART)</strong>.
                            </p>
                        </div>

                        <div class="blog__details__quote">
                            <p>
                                "Penilaian alternatif dihitung transparan menggunakan SMART — hasil akhirnya berupa perankingan yang mudah diaudit dan adil bagi semua calon penerima."
                            </p>
                        </div>

                        <div class="blog__details__title__more">
                            <h4>Latar Belakang</h4>
                            <p>
                                Tantangan utama seleksi penerima BPJS PBI adalah konsistensi penilaian dan keterlacakan keputusan. Meski regulasi teknis telah mengarahkan indikator kelayakan, proses manual rentan subjektif dan memakan waktu. Diperlukan pendekatan terstruktur agar keputusan tepat sasaran, adil, dan dapat dipertanggungjawabkan kepada masyarakat.
                            </p>
                        </div>

                        <div class="blog__details__title__more">
                            <h4>Tujuan Sistem</h4>
                            <ul class="pl-5 pb-5 pt-3">
                                <li>Menyeleksi calon penerima BPJS PBI secara objektif berbasis data.</li>
                                <li>Mengurangi ketidaktepatan sasaran serta mempercepat proses verifikasi kelayakan.</li>
                                <li>Menyediakan perankingan transparan sebagai rekomendasi bagi pengambil keputusan.</li>
                                <li>Meningkatkan akuntabilitas dalam penyaluran bantuan kesehatan kepada yang berhak.</li>
                            </ul>
                        </div>

                        <div class="blog__details__title__more">
                            <h4>Poin Penting BPJS PBI</h4>
                            <ul class="pl-5 pb-5 pt-3">
                                <li><strong>Iuran:</strong> Rp0 (Gratis/ditanggung pemerintah pusat atau daerah).</li>
                                <li><strong>Sasaran:</strong> Fakir miskin, orang tidak mampu, anak terlantar, lansia, disabilitas, dan penyandang masalah kesejahteraan sosial.</li>
                                <li><strong>Syarat:</strong> Terdaftar dalam Data Terpadu Kesejahteraan Sosial (DTKS) yang dikelola Kementerian Sosial.</li>
                                <li><strong>Layanan:</strong> Rawat jalan dan rawat inap kelas 3 di fasilitas kesehatan.</li>
                                <li><strong>Cek Status:</strong> Bisa melalui WhatsApp PANDAWA (0811-816-5165), aplikasi Mobile JKN, atau situs BPJS Kesehatan.</li>
                                <li><strong>Pengaktifan Kembali:</strong> Jika dinonaktifkan, bisa diaktifkan kembali melalui Dinas Sosial atau dinas kesehatan setempat jika masih memenuhi syarat, terutama untuk pasien penyakit kronis.</li>
                                <li><strong>Status Dinamis:</strong> Status PBI dapat berubah/nonaktif jika peserta dianggap sudah mampu secara ekonomi atau tidak terdaftar lagi dalam data DTKS.</li>
                            </ul>
                        </div>

                        <div class="blog__details__title__more">
                            <h4>Metodologi SMART</h4>
                            <p>
                                <strong>Penilaian Alternatif dengan SMART.</strong> Setiap kriteria dibentuk fungsi nilai (utility), dilakukan normalisasi (benefit/cost), lalu dihitung skor akhir sebagai penjumlahan tertimbang. Hasilnya berupa peringkat calon penerima BPJS PBI yang objektif dan terukur.
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
                                <li>Proses seleksi lebih cepat dan terdokumentasi dengan baik.</li>
                                <li>Meningkatkan transparansi dan akuntabilitas penyaluran bantuan kesehatan BPJS PBI.</li>
                                <li>Memastikan bantuan tepat sasaran kepada yang paling membutuhkan.</li>
                            </ul>
                        </div>

                        <div class="blog__details__item">
                            <div class="blog__details__item__pic">
                                <img src="{{ asset('fo/img/rusun.jpeg') }}" alt="Ilustrasi BPJS PBI">
                            </div>
                        </div>

                        <div class="blog__details__item">
                            <div class="blog__details__item__text">
                                <p>
                                    Dengan pendekatan SMART, proses seleksi calon penerima BPJS PBI menjadi lebih terstruktur, terukur, dan berkeadilan—mendukung misi menyediakan jaminan kesehatan bagi masyarakat yang benar-benar membutuhkan.
                                </p>
                            </div>
                        </div>

                        <div class="blog__details__desc pt-5">
                            <h4>Penutup</h4>
                            <p>
                                Kami berharap sistem SmartPBI ini menjadi referensi yang kuat dalam pengambilan keputusan penerima BPJS PBI. Saran dan masukan sangat kami nantikan demi penyempurnaan berkelanjutan.
                            </p>
                            <p>
                                Untuk pertanyaan lebih lanjut atau kolaborasi, silakan kunjungi halaman
                                <a href="{{ route('fo.ourteam.index') }}">kontak</a> kami.
                            </p>
                            <p>
                                Informasi lebih lanjut tentang BPJS PBI dapat diakses melalui situs resmi
                                <a href="https://www.bpjs-kesehatan.go.id/" target="_blank">BPJS Kesehatan</a>.
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Section End -->
@endsection
