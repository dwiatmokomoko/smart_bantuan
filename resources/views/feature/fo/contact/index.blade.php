@extends('app.app_fo')

@push('style')
<style>
/* ===== Usage Guide (ug-*) ===== */
.ug-wrap{max-width:1100px;margin:0 auto}
.ug-head{text-align:center;margin-bottom:1.25rem}
.ug-title{font-size:2.05rem;line-height:1.25;margin:0;font-weight:800;letter-spacing:.2px}
.ug-sub{color:#64748b;margin-top:.5rem}
.ug-divide{width:84px;height:4px;margin:.9rem auto 0;border-radius:9999px;background:linear-gradient(90deg,#0ea5e9,#6366f1)}

.ug-quick{display:flex;gap:.5rem;justify-content:center;flex-wrap:wrap;margin:1.2rem 0 1.6rem}
.ug-chip{
  display:inline-flex;align-items:center;gap:.5rem;
  padding:.5rem .8rem;border-radius:9999px;font-weight:700;
  border:1px solid #e2e8f0;background:#fff;color:#0f172a;
}
.ug-chip i{color:#0ea5e9}
.ug-chip:hover{background:#f8fafc}

.ug-grid{
  display:grid;gap:18px;
  grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
}

/* Cards */
.ug-card{
  background:#fff;border-radius:18px;padding:18px 20px;
  box-shadow:0 10px 22px rgba(2,6,23,.06);
  transition:transform .15s ease, box-shadow .15s ease;
}
.ug-card:hover{ transform:translateY(-2px); box-shadow:0 14px 26px rgba(2,6,23,.09) }

/* Step header */
.ug-step{
  display:flex;align-items:center;gap:.6rem;margin-bottom:.25rem
}
.ug-badge{
  width:34px;height:34px;display:inline-grid;place-items:center;
  border-radius:50%;
  background:linear-gradient(135deg,#0ea5e9,#6366f1);
  color:#fff;font-weight:800;font-size:.95rem;flex:0 0 auto;
  box-shadow:0 4px 10px rgba(14,165,233,.28);
}
.ug-h4{margin:0;font-size:1.06rem;font-weight:800;color:#0f172a}

/* Lines */
.ug-lines{display:flex;flex-direction:column;gap:8px;margin-top:.45rem}
.ug-line{position:relative;padding-left:16px;color:#334155}
.ug-line:before{
  content:"";position:absolute;left:0;top:.58em;width:6px;height:6px;
  border-radius:999px;background:#cbd5e1
}
.ug-hint{color:#64748b}

/* Breadcrumb spacing consistency */
.breadcrumb-section{margin-bottom:8px}
@media(max-width:991.98px){
  .ug-title{font-size:1.85rem}
}
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
  <div class="container ug-wrap">

    <div class="ug-head">
      <h2 class="ug-title">Petunjuk Penggunaan SmartPBI BPJS PBI</h2>
      <div class="ug-divide"></div>
      <div class="ug-sub">
        Alur singkat:
        <strong>Pra-Kelayakan → Registrasi → Login → Lengkapi Data → Upload Berkas → Riwayat</strong>
      </div>
    </div>

    {{-- Quick nav chips (opsional) --}}
    <div class="ug-quick">
      <span class="ug-chip"><i class="fa fa-circle-check"></i> Pra-Kelayakan</span>
      <span class="ug-chip"><i class="fa fa-user-plus"></i> Registrasi</span>
      <span class="ug-chip"><i class="fa fa-right-to-bracket"></i> Login</span>
      <span class="ug-chip"><i class="fa fa-list-check"></i> Lengkapi Data</span>
      <span class="ug-chip"><i class="fa fa-file-upload"></i> Upload Berkas</span>
      <span class="ug-chip"><i class="fa fa-clock-rotate-left"></i> Riwayat</span>
    </div>

    <div class="ug-grid">

      <!-- Langkah 1 -->
      <div class="ug-card">
        <div class="ug-step">
          <div class="ug-badge">1</div>
          <h4 class="ug-h4">Pra-Kelayakan (Wajib Sebelum Registrasi)</h4>
        </div>
        <div class="ug-lines">
          <div class="ug-line">Buka menu <strong>Register</strong> atau langsung halaman <strong>Login</strong>.</div>
          <div class="ug-line">Jawab 4 pertanyaan: Warga Kota Yogyakarta? PNS/TNI/Polri? Memiliki rumah? Sudah/pernah menikah?</div>
          <div class="ug-line ug-hint">Jika pola jawaban terpenuhi → diarahkan ke <strong>Registrasi</strong>.</div>
        </div>
      </div>

      <!-- Langkah 2 -->
      <div class="ug-card">
        <div class="ug-step">
          <div class="ug-badge">2</div>
          <h4 class="ug-h4">Registrasi & Login</h4>
        </div>
        <div class="ug-lines">
          <div class="ug-line">Lakukan <strong>Registrasi User</strong> (NIK, nama, lahir, alamat, email, no HP, sandi).</div>
          <div class="ug-line">Setelah registrasi, lanjut <strong>Login</strong> sebagai User.</div>
        </div>
      </div>

      <!-- Langkah 3 -->
      <div class="ug-card">
        <div class="ug-step">
          <div class="ug-badge">3</div>
          <h4 class="ug-h4">Mengisi Formulir Kelayakan BPJS PBI</h4>
        </div>
        <div class="ug-lines">
          <div class="ug-line">Masuk ke <strong>Pengajuan → Hitung Kelayakan</strong>.</div>
          <div class="ug-line">Isi variabel: <strong>Pekerjaan</strong>, <strong>Status Hubungan Dalam Keluarga</strong>, <strong>Data Kependudukan Sinkron</strong>, <strong>Adanya Anggota Keluarga Sudah Ditanggung Iuran BPJS</strong>, <strong>Adanya Anggota Keluarga di luar keluarga inti</strong>, <strong>Kependudukan Sesuai Wilayah PBI BPJS</strong>.</div>
          <div class="ug-line">Klik <strong>Lanjut</strong> untuk memproses.</div>
        </div>
      </div>

      <!-- Langkah 4 -->
      <div class="ug-card">
        <div class="ug-step">
          <div class="ug-badge">4</div>
          <h4 class="ug-h4">Upload Berkas</h4>
        </div>
        <div class="ug-lines">
          <div class="ug-line">Unggah berkas (foto/PDF): KTP, KK, Surat Pernyataan Belum Memiliki Rumah, Slip Gaji/Surat Penghasilan, SKCK.</div>
          <div class="ug-line ug-hint">Selesai upload, sistem otomatis mengarahkan ke <strong>Riwayat Pengajuan</strong>.</div>
        </div>
      </div>

      <!-- Langkah 5 -->
      <div class="ug-card">
        <div class="ug-step">
          <div class="ug-badge">5</div>
          <h4 class="ug-h4">Riwayat Pengajuan & Status</h4>
        </div>
        <div class="ug-lines">
          <div class="ug-line">Menu <strong>Pengajuan</strong> berubah menjadi <strong>Riwayat Pengajuan</strong> bila berkas sudah diunggah.</div>
          <div class="ug-line">Pantau status proses: <em>Pengajuan / Interview / Disetujui / Ditolak</em> beserta catatan admin.</div>
          <div class="ug-line">Semua berkas yang diunggah dapat dilihat/diunduh kembali dari halaman ini.</div>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection
