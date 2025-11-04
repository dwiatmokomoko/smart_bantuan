@extends('app.app_fo')

@section('content_fo')
  {{-- Breadcrumb --}}
  <div class="breadcrumb-section">
    <div class="container">
      <div class="row"><div class="col-lg-12">
        <div class="breadcrumb__option">
          <a href="{{ route('fo.home.index') }}"><span class="fa fa-home"></span> Home</a>
          <span>Upload Berkas</span>
        </div>
      </div></div>
    </div>
  </div>

  <section class="contact-form spad">
    <div class="container max-w-3xl">

      {{-- Alerts --}}
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if ($errors->any())
        <div class="alert alert-danger">
          <div class="mb-2"><strong>Terjadi kesalahan:</strong></div>
          <ul class="mb-0">
            @foreach ($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <h2 class="upload-title">Upload Berkas Persyaratan</h2>
      <p class="text-muted mb-4">
        Unggah berkas berikut dalam format <strong>JPG/PNG/PDF</strong> (maks. <strong>5MB</strong> per berkas).
      </p>

      <form method="POST"
            action="{{ route('fo.berkas.store', request('ticket') ? ['ticket' => request('ticket')] : []) }}"
            enctype="multipart/form-data"
            novalidate>
        @csrf

        {{-- Ikat dengan ticket dari query atau session --}}
        <input type="hidden" name="ticket" value="{{ request('ticket') ?? session('last_ticket') }}">

        {{-- KTP --}}
        <div class="upload-card">
          <div class="upload-header">
            <h5 class="upload-label">KTP <span class="text-danger">*</span></h5>
            <small class="text-muted">Format: JPG/PNG/PDF, maks 5MB.</small>
          </div>

          {{-- NOTE: gunakan LABEL sebagai wrapper agar klik wrapper otomatis memicu input tanpa JS --}}
          <label class="drop" for="file-ktp" data-field="ktp" aria-label="Pilih/seret berkas KTP">
            <div class="drop-icon"></div>
            <div class="drop-text">
              <strong>Klik untuk memilih</strong> atau <em>seret & letakkan</em> berkas di sini
            </div>
            <div class="drop-meta">JPG, PNG atau PDF &middot; ≤ 5MB</div>
            <input type="file" name="ktp" id="file-ktp"
                   accept="image/*,application/pdf" required>
          </label>

          <div class="file-pill d-none" id="pill-ktp">
            <span class="file-name">-</span>
            <button type="button" class="pill-remove" data-target="file-ktp" aria-label="Hapus KTP">×</button>
          </div>
        </div>

        {{-- KK --}}
        <div class="upload-card">
          <div class="upload-header">
            <h5 class="upload-label">KK <span class="text-danger">*</span></h5>
            <small class="text-muted">Format: JPG/PNG/PDF, maks 5MB.</small>
          </div>

          <label class="drop" for="file-kk" data-field="kk" aria-label="Pilih/seret berkas KK">
            <div class="drop-icon"></div>
            <div class="drop-text"><strong>Klik untuk memilih</strong> atau <em>seret & letakkan</em> berkas</div>
            <div class="drop-meta">JPG, PNG atau PDF &middot; ≤ 5MB</div>
            <input type="file" name="kk" id="file-kk"
                   accept="image/*,application/pdf" required>
          </label>

          <div class="file-pill d-none" id="pill-kk">
            <span class="file-name">-</span>
            <button type="button" class="pill-remove" data-target="file-kk" aria-label="Hapus KK">×</button>
          </div>
        </div>

        {{-- Surat Pernyataan Belum Memiliki Rumah --}}
        <div class="upload-card">
          <div class="upload-header">
            <h5 class="upload-label">Surat Pernyataan Belum Memiliki Rumah <span class="text-danger">*</span></h5>
            <small class="text-muted">Format: JPG/PNG/PDF, maks 5MB.</small>
          </div>

          <label class="drop" for="file-surat" data-field="surat_belum_memiliki_rumah"
                 aria-label="Pilih/seret berkas surat belum memiliki rumah">
            <div class="drop-icon"></div>
            <div class="drop-text"><strong>Klik untuk memilih</strong> atau <em>seret & letakkan</em> berkas</div>
            <div class="drop-meta">JPG, PNG atau PDF &middot; ≤ 5MB</div>
            <input type="file" name="surat_belum_memiliki_rumah" id="file-surat"
                   accept="image/*,application/pdf" required>
          </label>

          <div class="file-pill d-none" id="pill-surat">
            <span class="file-name">-</span>
            <button type="button" class="pill-remove" data-target="file-surat" aria-label="Hapus Surat">×</button>
          </div>
        </div>

        {{-- Slip Gaji / Surat Penghasilan --}}
        <div class="upload-card">
          <div class="upload-header">
            <h5 class="upload-label">Slip Gaji / Surat Pernyataan Penghasilan <span class="text-danger">*</span></h5>
            <small class="text-muted">Format: JPG/PNG/PDF, maks 5MB.</small>
          </div>

          <label class="drop" for="file-gaji" data-field="slip_gaji"
                 aria-label="Pilih/seret berkas slip gaji / surat penghasilan">
            <div class="drop-icon"></div>
            <div class="drop-text"><strong>Klik untuk memilih</strong> atau <em>seret & letakkan</em> berkas</div>
            <div class="drop-meta">JPG, PNG atau PDF &middot; ≤ 5MB</div>
            <input type="file" name="slip_gaji" id="file-gaji"
                   accept="image/*,application/pdf" required>
          </label>

          <div class="file-pill d-none" id="pill-gaji">
            <span class="file-name">-</span>
            <button type="button" class="pill-remove" data-target="file-gaji" aria-label="Hapus Slip">×</button>
          </div>
        </div>

        {{-- SKCK --}}
        <div class="upload-card">
          <div class="upload-header">
            <h5 class="upload-label">SKCK <span class="text-danger">*</span></h5>
            <small class="text-muted">Format: JPG/PNG/PDF, maks 5MB.</small>
          </div>

          <label class="drop" for="file-skck" data-field="skck" aria-label="Pilih/seret berkas SKCK">
            <div class="drop-icon"></div>
            <div class="drop-text"><strong>Klik untuk memilih</strong> atau <em>seret & letakkan</em> berkas</div>
            <div class="drop-meta">JPG, PNG atau PDF &middot; ≤ 5MB</div>
            <input type="file" name="skck" id="file-skck"
                   accept="image/*,application/pdf" required>
          </label>

          <div class="file-pill d-none" id="pill-skck">
            <span class="file-name">-</span>
            <button type="button" class="pill-remove" data-target="file-skck" aria-label="Hapus SKCK">×</button>
          </div>
        </div>

        {{-- Actions --}}
        <div class="form-actions">
          <a href="{{ route('fo.home.index') }}" class="btn btn-outline-secondary">Batal</a>
          <button type="submit" class="btn btn-primary">Kirim Berkas</button>
        </div>
      </form>

      <p class="text-muted mt-5">
        Pastikan setiap berkas jelas terbaca. Jika berupa foto, usahakan tidak buram dan semua sudut dokumen terlihat.
      </p>
    </div>
  </section>
@endsection

@push('style')
<style>
  .max-w-3xl { max-width: 860px; }
  .upload-title { font-weight: 700; margin-bottom: .5rem; }
  .upload-card {
    background: #fff; border: 1px solid #eef0f3; border-radius: 14px;
    padding: 18px; box-shadow: 0 6px 18px rgba(20,20,20,.05);
    margin-bottom: 22px;
  }
  .upload-header { display:flex; justify-content:space-between; align-items:baseline; margin-bottom: 10px; }
  .upload-label { margin:0; font-weight: 600; }

  /* Drop area (label) */
  .drop {
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    gap:6px; min-height: 140px; border: 2px dashed #cbd5e1; border-radius: 12px;
    background: #fafcff; cursor: pointer; transition: all .2s ease;
    padding: 16px; text-align:center;
  }
  .drop:hover { border-color:#86b7fe; background:#f3f7ff; }
  .drop-icon { font-size: 28px; line-height: 1; }
  .drop-text { font-size: .95rem; }
  .drop-meta { font-size: .8rem; color:#6b7280; }
  .drop input[type=file] {
    position:absolute; inset:0; width:1px; height:1px; opacity:0; pointer-events:auto;
  }
  .drop.dragover { border-color:#60a5fa; background:#eef6ff; }

  /* Selected file pill */
  .file-pill {
    display:inline-flex; align-items:center; gap:10px; margin-top:12px;
    padding:8px 12px; border-radius:999px; background:#f1f5f9; border:1px solid #e2e8f0;
  }
  .file-pill .file-name { font-size:.9rem; }
  .pill-remove {
    border:0; background:#ef4444; color:#fff; width:24px; height:24px; line-height:24px;
    border-radius:50%; cursor:pointer; display:inline-flex; align-items:center; justify-content:center;
  }
  .d-none { display:none !important; }

  /* Actions */
  .form-actions {
    display:flex; gap:12px; justify-content:flex-end; margin-top: 28px;
  }

  /* Utility */
  .text-muted { color:#6b7280 !important; }
</style>
@endpush

@push('script')
<script>
  // Helper untuk siapkan 1 drop area
  function setupDrop(field) {
    const dz   = document.querySelector(`label.drop[for="file-${field}"]`);
    const file = document.getElementById(`file-${field}`);
    const pill = document.getElementById(`pill-${field}`);
    const nameEl = pill?.querySelector('.file-name');
    const rmBtn  = pill?.querySelector('.pill-remove');

    if (!dz || !file) return;

    // --- Penting: TIDAK ada manual file.click() di sini ---
    // <label> otomatis memicu input file saat diklik.

    // Drag & Drop
    ['dragenter','dragover'].forEach(evt =>
      dz.addEventListener(evt, e => { e.preventDefault(); e.stopPropagation(); dz.classList.add('dragover'); })
    );
    ['dragleave','drop'].forEach(evt =>
      dz.addEventListener(evt, e => { e.preventDefault(); e.stopPropagation(); dz.classList.remove('dragover'); })
    );
    dz.addEventListener('drop', e => {
      const dt = e.dataTransfer;
      if (dt && dt.files && dt.files[0]) {
        file.files = dt.files;   // assign ke input
        updatePill(dt.files[0]);
      }
    });

    // Perubahan file via dialog
    file.addEventListener('change', () => {
      if (file.files && file.files[0]) updatePill(file.files[0]);
    });

    function updatePill(f) {
      if (!pill) return;
      nameEl.textContent = `${f.name} (${(f.size/1024/1024).toFixed(2)} MB)`;
      pill.classList.remove('d-none');
    }

    rmBtn?.addEventListener('click', () => {
      file.value = '';
      pill.classList.add('d-none');
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    ['ktp','kk','surat','gaji','skck'].forEach(setupDrop);
  });
</script>
@endpush
