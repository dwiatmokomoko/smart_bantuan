{{-- resources/views/feature/fo/pengajuan/history.blade.php --}}
@extends('app.app_fo')

@push('style')
<style>
  .history-table thead th { border-bottom-width:2px; }

  /* Pills container */
  .files-pills { display:flex; flex-wrap:wrap; gap:.1rem; }

  /* Base pill */
  .pill {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.32rem .5rem; border-radius:9999px; font-size:.72rem; font-weight:500;
    text-decoration:none; border:1px solid transparent; transition:all .15s ease;
    box-shadow: 0 1px 0 rgba(16,24,40,.04), inset 0 0 0 1px rgba(255,255,255,.4);
  }
  .pill:focus { outline: none; box-shadow: 0 0 0 3px rgba(14,165,233,.25); }

  /* Color variants */
  .pill-ktp {  background:#eef2ff; color:#3730a3; border-color:#c7d2fe; }
  .pill-kk  {  background:#ecfeff; color:#155e75; border-color:#a5f3fc; }
  .pill-sp  {  background:#fff7ed; color:#9a3412; border-color:#fed7aa; }   /* Surat Perny. */
  .pill-sg  {  background:#f0fdf4; color:#166534; border-color:#bbf7d0; }   /* Slip Gaji */
  .pill-sk  {  background:#fef2f2; color:#991b1b; border-color:#fecaca; }   /* SKCK */

  .pill:hover { filter: brightness(.95); text-decoration:none; }

  /* tiny icon using CSS mask (no CDN) */
  .ico { width:14px; height:14px; display:inline-block; background: currentColor; }
  .ico-doc { -webkit-mask:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="%23000" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Zm0 2 4 4h-4z"/></svg>') no-repeat center/contain; mask:url(data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="%23000" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Zm0 2 4 4h-4z"/></svg>) no-repeat center/contain; }
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

    @if($berkas->isEmpty())
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
              <th style="min-width:380px">Berkas</th>
              <th>Keterangan</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
          @foreach($berkas as $i => $row)
            @php
              $badge = match($row->status) {
                'approved'  => 'badge bg-success',
                'rejected'  => 'badge bg-danger',
                'interview' => 'badge bg-info text-dark',
                default     => 'badge bg-warning text-dark'
              };
              $label = \Illuminate\Support\Str::title($row->status ?? 'Pengajuan');

              $makePill = function(string $text, ?string $path, string $variant) {
                  if (!$path) return '';
                  $url = Storage::url($path);
                  return '<a class="pill '.$variant.'" href="'.$url.'" target="_blank" rel="noopener">
                            <i class="ico ico-doc"></i><span>'.$text.'</span>
                          </a>';
              };
            @endphp
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $row->created_at->format('d M Y H:i') }}</td>
              <td><code>{{ $row->ticket ?? '-' }}</code></td>
              <td><span class="{{ $badge }}">{{ $label }}</span></td>
              <td>
                <div class="files-pills">
                  {!! $makePill('KTP',               $row->ktp_path,  'pill-ktp') !!}
                  {!! $makePill('KK',                $row->kk_path,   'pill-kk')  !!}
                  {!! $makePill('SPBMR',      $row->surat_belum_memiliki_rumah_path, 'pill-sp') !!}
                  {!! $makePill('Slip Gaji',         $row->slip_gaji_path, 'pill-sg') !!}
                  {!! $makePill('SKCK',              $row->skck_path, 'pill-sk')  !!}
                </div>
              </td>
              <td class="text-muted">{{ $row->notes ?: '—' }}</td>
              <td class="text-center">
                @if($row->status === 'rejected')
                  <a href="{{ route('fo.count.index') }}" class="btn btn-sm btn-outline-primary">Ajukan Ulang</a>
                @else
                  —
                @endif
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    @endif
  </div>
</section>
@endsection
