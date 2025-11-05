{{-- resources/views/feature/fo/pengajuan/history.blade.php --}}
@extends('app.app_fo')

@push('style')
<style>
  .history-table thead th{border-bottom-width:2px}
  .history-table td,.history-table th{padding-top:.64rem; padding-bottom:.64rem}

  /* Chip buttons (mini) */
  .btn-pill{
    display:inline-flex; align-items:center; gap:.42rem;
    padding:.28rem .56rem; font-size:.78rem; line-height:1;
    font-weight:600; border-radius:9999px; border:1px solid transparent;
    transition:all .15s ease; text-decoration:none;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,.6), 0 1px 0 rgba(16,24,40,.04);
  }
  .btn-pill--sm{ padding:.24rem .5rem; font-size:.75rem }
  .btn-pill .fa{ font-size:.85em; opacity:.9 }

  /* Variants */
  .btn-berkas{ background:#eef2ff; color:#4338ca; border-color:#c7d2fe }
  .btn-berkas:hover{ filter:brightness(.97) }
  .btn-berkas:focus{ box-shadow:0 0 0 3px rgba(99,102,241,.25) }

  .btn-kriteria{ background:#ecfeff; color:#0e7490; border-color:#a5f3fc }
  .btn-kriteria:hover{ filter:brightness(.97) }
  .btn-kriteria:focus{ box-shadow:0 0 0 3px rgba(14,165,233,.25) }

  .btn-ulang{ background:#fef2f2; color:#b91c1c; border-color:#fecaca }
  .btn-ulang:hover{ filter:brightness(.97) }
  .btn-ulang:focus{ box-shadow:0 0 0 3px rgba(239,68,68,.2) }

  /* Chip group spacing */
  .chip-group{ display:flex; flex-wrap:wrap; gap:.6rem }     /* lebih lega */
</style>
@endpush

@section('content_fo')
  <div class="breadcrumb-section">
    <div class="container">
      <div class="row"><div class="col-lg-12">
        <div class="breadcrumb__option">
          <a href="{{ route('fo.home.index') }}"><span class="fa fa-home"></span> Home</a>
          <span>Riwayat Pengajuan</span>
        </div>
      </div></div>
    </div>
  </div>

  <section class="hosting-section spad">
    <div class="container">
      <div class="section-title text-center">
        <h3 class="mb-3">RIWAYAT PENGAJUAN</h3>
      </div>

      @if ($berkas->isEmpty())
        <div class="text-center">
          <p class="mb-2">Belum ada pengajuan. Silakan lakukan perhitungan & unggah berkas.</p>
          <a href="{{ route('fo.count.index') }}" class="btn btn-primary">Mulai Pengajuan</a>
        </div>
      @else
        <div class="table-responsive">
          <table class="table history-table align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Kode Pendaftaran</th>
                <th>Status</th>
                <th style="min-width:230px">Data</th>
                <th>Keterangan</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($berkas as $i => $row)
                @php
                  $badge = match ($row->status) {
                    'approved'  => 'badge bg-success',
                    'rejected'  => 'badge bg-danger',
                    'interview' => 'badge bg-info text-dark',
                    default     => 'badge bg-warning text-dark',
                  };
                  $label = \Illuminate\Support\Str::title($row->status ?? 'Pengajuan');
                @endphp
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td>{{ $row->created_at->format('d M Y H:i') }}</td>
                  <td><code>{{ $row->ticket ?? '-' }}</code></td>
                  <td><span class="{{ $badge }}">{{ $label }}</span></td>

                  {{-- Data (chips) --}}
                  <td>
                    <div class="chip-group">
                      <a href="{{ route('fo.pengajuan.berkas', $row->ticket) }}"
                         class="btn btn-pill btn-pill--sm btn-berkas">
                        <i class="fa fa-folder-open me-1"></i> Berkas
                      </a>
                      <a href="{{ route('fo.pengajuan.kriteria', $row->ticket) }}"
                         class="btn btn-pill btn-pill--sm btn-kriteria">
                        <i class="fa fa-list-check me-1"></i> Data Diri
                      </a>
                    </div>
                  </td>

                  <td class="text-muted">{{ $row->notes ?: '—' }}</td>

                  {{-- Aksi (chip juga) --}}
                  <td class="text-center">
                    @if ($row->status === 'rejected')
                      <a href="{{ route('fo.count.index') }}"
                         class="btn btn-pill btn-pill--sm btn-ulang">
                        <i class="fa fa-rotate-left me-1"></i> Ajukan Ulang
                      </a>
                    @else
                      —
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
    <br><br><br><br><br><br><br><br><br>
  </section>
@endsection
