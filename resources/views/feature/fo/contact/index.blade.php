@extends('app.app_fo')

@push('style')
<style>
/* ===== SiRuSmart – Usage Guide Cards (namespace gc-*) ===== */
.gc-wrap{max-width:1024px;margin:0 auto}
.gc-title{font-size:2rem;line-height:1.25;margin-bottom:.35rem}
.gc-sub{color:#64748b;margin-bottom:1.1rem}
.gc-cta{display:flex;gap:.6rem;flex-wrap:wrap;margin:1rem 0 1.6rem}
.gc-btn{display:inline-flex;align-items:center;gap:.5rem;padding:.6rem .95rem;border-radius:12px;
        font-weight:600;text-decoration:none;border:1px solid transparent;transition:.2s}
.gc-btn-primary{background:#0d6efd;color:#fff}.gc-btn-primary:hover{filter:brightness(.95)}
.gc-btn-ghost{background:#fff;border:1px solid #cbd5e1;color:#0f172a}.gc-btn-ghost:hover{background:#f8fafc}

/* Cards grid */
.gc-grid{display:grid;gap:16px}
@media(min-width:992px){.gc-grid{grid-template-columns:1fr 1fr}}
.gc-card{background:#fff;border-radius:18px;padding:18px 20px;box-shadow:0 10px 22px rgba(2,6,23,.06)}
/* Step header */
.gc-step{font-weight:800;color:#0d6efd;margin-bottom:6px;letter-spacing:.02em}
.gc-h4{margin:0 0 10px 10px;font-size:1.05rem} /* judul agak menjorok ke kanan */
/* Lines (mengganti bullet list, tanpa <ul>) */
.gc-lines{display:flex;flex-direction:column;gap:7px}
.gc-line{position:relative;padding-left:16px;color:#334155}
.gc-line:before{content:"";position:absolute;left:0;top:.55em;width:6px;height:6px;border-radius:999px;background:#cbd5e1}
/* Hint kecil */
.gc-hint{color:#64748b;font-size:.95rem;margin-top:.25rem}
/* Spasi antar-langkah ekstra di mobile */
@media(max-width:991.98px){.gc-card{padding:18px 18px}}
</style>
@endpush

@section('content_fo')

<!-- Breadcrumb -->
<div class="breadcrumb-section">
  <div class="container">
    <div class="row"><div class="col-lg-12">
      <div class="breadcrumb__option">
        <a href="{{ route('fo.home.index') }}"><span class="fa fa-home"></span> Home</a>
        <span>Petunjuk Penggunaan</span>
      </div>
    </div></div>
  </div>
</div>

<section class="spad">
  <div class="container gc-wrap">

    <h2 class="gc-title">Petunjuk Penggunaan Sistem Klasifikasi Penerima Rusunawa</h2>
    <br>
    <div class="gc-sub">Alur singkat: <strong>Pra-Kelayakan → Registrasi → Login → Hitung → (LAYAK) Upload Berkas → Riwayat</strong></div>

    <div class="gc-cta">
      <a href="{{ route('pre-eligibility.form') }}" class="gc-btn gc-btn-primary"><i class="fa fa-check-circle"></i> Mulai Pra-Kelayakan</a>
      @guest('web')
        <a href="{{ route('user.login') }}" class="gc-btn gc-btn-ghost"><i class="fa fa-sign-in"></i> Login User</a>
      @else
        <a href="{{ route('fo.count.index') }}" class="gc-btn gc-btn-ghost"><i class="fa fa-calculator"></i> Hitung Kelayakan</a>
      @endguest
    </div>
<br>
    <div class="gc-grid">

      <!-- Langkah 1 -->
      <div class="gc-card">
        <div class="gc-step">Langkah 1</div>
        <h4 class="gc-h4">Pra-Kelayakan (Wajib Sebelum Registrasi)</h4>
        <div class="gc-lines">
          <div class="gc-line">Buka menu <strong>Register</strong> atau langsung halaman Login.</div>
          <div class="gc-line">Jawab 4 pertanyaan: Warga Kota Yogyakarta? PNS/TNI/Polri? Memiliki rumah? Sudah/pernah menikah?</div>
          <div class="gc-line gc-hint">Jika pola jawaban terpenuhi → diarahkan ke <strong>Registrasi</strong>.</div>
        </div>
      </div>
<br>
      <!-- Langkah 2 -->
      <div class="gc-card">
        <div class="gc-step">Langkah 2</div>
        <h4 class="gc-h4">Registrasi & Login</h4>
        <div class="gc-lines">
          <div class="gc-line">Lakukan <strong>Registrasi User</strong> (NIK, nama, lahir, alamat, email, no HP, sandi).</div>
          <div class="gc-line">Setelah registrasi, lanjut <strong>Login</strong> sebagai User.</div>
        </div>
      </div>
<br>
      <!-- Langkah 3 -->
      <div class="gc-card">
        <div class="gc-step">Langkah 3</div>
        <h4 class="gc-h4">Mengisi Formulir Kelayakan</h4>
        <div class="gc-lines">
          <div class="gc-line">Masuk ke <strong>Pengajuan → Hitung Kelayakan</strong>.</div>
          <div class="gc-line">Isi variabel: <strong>Penghasilan</strong> (kategori), <strong>Pekerjaan</strong>, <strong>Status Perkawinan</strong>, <strong>Calon Penghuni</strong> (sendiri/bersama keluarga), <strong>Status Penempatan</strong>.</div>
          <div class="gc-line">Klik <strong>Lanjut</strong> untuk memproses.</div>
        </div>
      </div>
<br>
      <!-- Langkah 4 -->
      <div class="gc-card">
        <div class="gc-step">Langkah 4</div>
        <h4 class="gc-h4">Hasil Prediksi</h4>
        <div class="gc-lines">
          <div class="gc-line">Sistem menampilkan rekomendasi <strong>LAYAK</strong> atau <strong>TIDAK LAYAK</strong>.</div>
          <div class="gc-line">Jika <strong>LAYAK</strong>, tombol <strong>Upload Berkas</strong> muncul pada halaman hasil.</div>
        </div>
      </div>
<br>
      <!-- Langkah 5 -->
      <div class="gc-card">
        <div class="gc-step">Langkah 5</div>
        <h4 class="gc-h4">Upload Berkas (Untuk Hasil LAYAK)</h4>
        <div class="gc-lines">
          <div class="gc-line">Unggah berkas (foto/PDF): KTP, KK, Surat Pernyataan Belum Memiliki Rumah, Slip Gaji/Surat Penghasilan, SKCK.</div>
          <div class="gc-line gc-hint">Selesai upload, sistem otomatis mengarahkan ke <strong>Riwayat Pengajuan</strong>.</div>
        </div>
      </div>
<br>
      <!-- Langkah 6 -->
      <div class="gc-card">
        <div class="gc-step">Langkah 6</div>
        <h4 class="gc-h4">Riwayat Pengajuan & Status</h4>
        <div class="gc-lines">
          <div class="gc-line">Menu <strong>Pengajuan</strong> berubah menjadi <strong>Riwayat Pengajuan</strong> bila berkas sudah diunggah.</div>
          <div class="gc-line">Pantau status proses: <em>Menunggu Verifikasi / Disetujui / Ditolak</em> beserta catatan admin.</div>
          <div class="gc-line">Semua berkas yang diunggah dapat dilihat/diunduh kembali dari halaman ini.</div>
        </div>
      </div>
<br>
    </div>

    <div class="gc-cta" style="margin-top:18px">
      <a href="{{ route('pre-eligibility.form') }}" class="gc-btn gc-btn-primary">Mulai Pra-Kelayakan</a>
      @auth('web')
        <a href="{{ route('fo.count.index') }}" class="gc-btn gc-btn-ghost">Hitung Kelayakan</a>
      @endauth
    </div>

  </div>
</section>
@endsection
