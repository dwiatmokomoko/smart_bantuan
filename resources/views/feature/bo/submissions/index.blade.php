{{-- resources/views/feature/bo/submissions/index.blade.php --}}
@extends('app.app')

@push('style')
<link rel="stylesheet" href="{{ asset('bo/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  .dt-inputs .form-control{max-width:220px}
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="card mt-4">
    <div class="card-body">

      <h5 class="card-title fw-semibold mb-4">Daftar Pengajuan Rusunawa</h5>

      {{-- Filter tanggal --}}
      <div class="row g-2 dt-inputs mb-3">
        <div class="col-auto">
          <input type="date" id="start_date" class="form-control" placeholder="Mulai">
        </div>
        <div class="col-auto">
          <input type="date" id="end_date" class="form-control" placeholder="Selesai">
        </div>
        <div class="col-auto">
          <button id="btnFilter" class="btn btn-primary"><i class="ti ti-filter"></i> Filter</button>
          <button id="btnReset" class="btn btn-outline-secondary"><i class="ti ti-reload"></i> Reset</button>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-striped" id="submissions_table">
          <thead>
            <tr>
              <th class="text-center">No</th>
              <th>Nama</th>
              <th>No HP</th>
              <th>Nilai Kelayakan</th>
              <th>Tanggal Pengajuan</th>
              <th>Status Berkas</th>
              <th>Keterangan</th> {{-- NEW --}}
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Modal Tolak --}}
<div class="modal fade" id="modalReject" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <form id="formReject" method="POST">
        @csrf
        <input type="hidden" name="status" value="rejected">
        <div class="modal-header">
          <h5 class="modal-title">Tolak Pengajuan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="x"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Nama Pemohon</label>
            <input type="text" id="rejectNama" class="form-control" readonly>
          </div>
          <div class="mb-2">
            <label class="form-label">Keterangan / Alasan Penolakan</label>
            <textarea class="form-control" name="notes" id="rejectNotes" rows="4" placeholder="Contoh: KTP tidak sesuai, KK buram, dsb" required></textarea>
          </div>
          <div class="text-muted small">Keterangan ini akan muncul di tabel & bisa disertakan pada pesan WhatsApp.</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('script')
<script src="{{ asset('bo/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('bo/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
$(function () {
  const table = $('#submissions_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "{{ route('admin.submissions.data', [], false) }}",
      data: function(d){
        d.start_date = $('#start_date').val() || '';
        d.end_date   = $('#end_date').val() || '';
      }
    },
    order: [[4, 'desc']], // kolom "Tanggal Pengajuan"
    columns: [
      {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false, className:'text-center'},
      {data:'user_name',   name:'u.name'},
      {data:'no_hp',       name:'u.no_hp', defaultContent:'-'},
      {data:'prob_layak',  name:'dt.prob_layak'},
      {data:'created_at',  name:'ub.created_at'},
      {data:'berkas_status', name:'ub.status', orderable:false, searchable:false},
      {data:'notes',       name:'ub.notes'}, // Keterangan
      {data:'action',      name:'action', orderable:false, searchable:false, className:'text-center'}
    ]
  });

  $('#btnFilter').on('click', () => table.ajax.reload());
  $('#btnReset').on('click', function(){
    $('#start_date, #end_date').val('');
    table.ajax.reload();
  });

  // Buka modal Tolak
  $(document).on('click', '.btn-reject', function(){
    const url  = $(this).data('url');
    const nama = $(this).data('nama');
    $('#formReject').attr('action', url);
    $('#rejectNama').val(nama);
    $('#rejectNotes').val('');
    const modal = new bootstrap.Modal(document.getElementById('modalReject'));
    modal.show();
  });

  // Submit Tolak (AJAX agar tabel langsung refresh)
  $('#formReject').on('submit', function(e){
    e.preventDefault();
    const $f = $(this);
    $.post($f.attr('action'), $f.serialize())
      .done(function(){
        bootstrap.Modal.getInstance(document.getElementById('modalReject')).hide();
        table.ajax.reload(null, false);
      })
      .fail(function(xhr){
        alert('Gagal memperbarui status. ' + (xhr.responseJSON?.message || ''));
      });
  });
});
</script>
@endpush
