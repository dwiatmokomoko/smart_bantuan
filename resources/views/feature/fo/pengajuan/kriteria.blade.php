@extends('app.app_fo')

@push('style')
<style>
  .card { border-radius:16px; border:1px solid #eef2f7; box-shadow: 0 6px 16px rgba(16,24,40,.06); }
  .stat { font-size:.88rem; }
  .prob-badge { font-weight:700; padding:.35rem .6rem; border-radius:9999px; }
  .prob-low { background:#fff1f2; color:#991b1b; }
  .prob-mid { background:#fef9c3; color:#78350f; }
  .prob-high{ background:#ecfdf5; color:#065f46; }
  .table tr td:first-child { width:38%; color:#64748b; }
</style>
@endpush

@section('content_fo')
<div class="breadcrumb-section">
  <div class="container">
    <div class="row"><div class="col-lg-12">
      <div class="breadcrumb__option">
        <a href="{{ route('fo.home.index') }}"><span class="fa fa-home"></span> Home</a>
        <span>Detail Data Diri</span>
      </div>
    </div></div>
  </div>
</div>

<section class="hosting-section spad">
  <div class="container">
    <div class="mb-4">
      <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left me-1"></i> Kembali</a>
    </div>

    <div class="card p-4">
      <div class="row g-3 mb-3">
        <div class="col-md-4 stat"><strong>Nama</strong><div>{{ $meta['name'] ?? '-' }}</div></div>
        <div class="col-md-4 stat"><strong>Kode Pendaftaran</strong><div><code>{{ $meta['ticket'] ?? '-' }}</code></div></div>
      </div>



      <hr>
      <hr>

      <h5 class="mb-3">Data Diri</h5>
      @if(empty($labels))
        <div class="text-muted">Data diri tidak tersedia.</div>
      @else
        <div class="table-responsive">
          <table class="table mb-0">
            <tbody>
              <tr><td>Penghasilan</td><td class="ps-3">{{ $labels['penghasilan'] ?? '—' }}</td></tr>
              <tr><td>Pekerjaan</td><td class="ps-3">{{ $labels['pekerjaan'] ?? '—' }}</td></tr>
              <tr><td>Status Perkawinan</td><td class="ps-3">{{ $labels['perkawinan'] ?? '—' }}</td></tr>
              <tr><td>Calon Penghuni</td><td class="ps-3">{{ $labels['calon_penghuni'] ?? '—' }}</td></tr>
              <tr><td>Status Penempatan</td><td class="ps-3">{{ $labels['status_penempatan'] ?? '—' }}</td></tr>
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
  <br><br><br><br><br><br><br><br><br><br>
</section>
@endsection
