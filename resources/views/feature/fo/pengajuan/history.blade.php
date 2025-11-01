@extends('app.app_fo')

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
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="section-title"><h3>Riwayat Pengajuan</h3></div>

    @if($berkas->isEmpty())
      <p class="mb-0">Belum ada pengajuan. Silakan lakukan perhitungan & unggah berkas.</p>
      <a href="{{ route('fo.count.index') }}" class="btn btn-primary mt-3">Mulai Pengajuan</a>
    @else
      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Tanggal</th>
              <th>Ticket</th>
              <th>Status</th>
              <th>Berkas</th>
            </tr>
          </thead>
          <tbody>
          @foreach($berkas as $i => $row)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $row->created_at->format('d M Y H:i') }}</td>
              <td>{{ $row->ticket ?? '-' }}</td>
              <td>
                @php
                  $cls = match($row->status) {
                    'approved' => 'badge bg-success',
                    'rejected' => 'badge bg-danger',
                    default    => 'badge bg-warning text-dark'
                  };
                  $label = ucfirst($row->status);
                @endphp
                <span class="{{ $cls }}">{{ $label }}</span>
                @if($row->notes)
                  <div class="small text-muted mt-1">Catatan: {{ $row->notes }}</div>
                @endif
              </td>
              <td>
                <div class="d-flex flex-wrap gap-2">
                  <a class="btn btn-sm btn-outline-secondary"
                     href="{{ Storage::url($row->ktp_path) }}" target="_blank">KTP</a>
                  <a class="btn btn-sm btn-outline-secondary"
                     href="{{ Storage::url($row->kk_path) }}" target="_blank">KK</a>
                  <a class="btn btn-sm btn-outline-secondary"
                     href="{{ Storage::url($row->surat_belum_memiliki_rumah_path) }}" target="_blank">Surat Perny.</a>
                  <a class="btn btn-sm btn-outline-secondary"
                     href="{{ Storage::url($row->slip_gaji_path) }}" target="_blank">Slip Gaji/Surat</a>
                  <a class="btn btn-sm btn-outline-secondary"
                     href="{{ Storage::url($row->skck_path) }}" target="_blank">SKCK</a>
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    @endif
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
  </div>
</section>
@endsection
