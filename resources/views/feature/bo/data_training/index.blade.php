{{-- resources/views/feature/bo/submissions/index.blade.php --}}
@extends('app.app')

@push('style')
<link rel="stylesheet" href="{{ asset('bo/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  .filters .form-control { max-width: 220px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="card mt-4">
    <div class="card-body">
      <h5 class="card-title fw-semibold mb-4">Daftar Pengajuan Rusunawa</h5>

      {{-- Filter tanggal --}}
      <div class="row g-2 mb-3 filters">
        <div class="col-auto">
          <input type="date" id="date_from" class="form-control" placeholder="Dari tanggal">
        </div>
        <div class="col-auto">
          <input type="date" id="date_to" class="form-control" placeholder="Sampai tanggal">
        </div>
        <div class="col-auto">
          <button id="btnFilter" class="btn btn-outline-primary"><i class="ti ti-filter"></i> Filter</button>
          <button id="btnReset" class="btn btn-outline-secondary ms-1"><i class="ti ti-refresh"></i> Reset</button>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-striped" id="submissions_table">
          <thead>
            <tr>
              <th class="text-center">No</th>
              <th>Nama</th>
              <th>No HP</th>
              <th>Prob. Layak</th>
              <th>Tanggal Pengajuan</th>
              <th>Status Berkas</th>
              <th>Keterangan</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Modal tolak (isi keterangan) --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="rejectForm" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tolak Pengajuan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="reject_id">
        <div class="mb-2">
          <label class="form-label">Nama Pemohon</label>
          <input type="text" id="reject_name" class="form-control" readonly>
        </div>
        <div class="mb-2">
          <label class="form-label">Keterangan / Alasan Penolakan</label>
          <textarea id="reject_notes" class="form-control" rows="4" placeholder="Contoh: KTP tidak sesuai / dokumen kurang jelas / dsb."></textarea>
        </div>
        <div class="text-muted small">
          *Keterangan ini akan tersimpan dan otomatis disisipkan ke template pesan WhatsApp.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger">Tolak & Simpan</button>
      </div>
    </form>
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
      data: function (d) {
        d.date_from = $('#date_from').val();
        d.date_to   = $('#date_to').val();
      }
    },
    order: [[4, 'desc']], // default: Tanggal Pengajuan desc
    columns: [
      {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false, className:'text-center'},
      {data:'user_name',   name:'u.name'},
      {data:'no_hp',       name:'u.no_hp', defaultContent:'-'},
      {
        data:'prob_layak',
        name:'dt.prob_layak',
        render: function(data, type){
          if (data === null || data === '-' || data === '') return (type === 'sort' ? 0 : '-');
          const val = parseFloat(data);
          if (type === 'sort' || type === 'type') return val;
          return val.toFixed(6);
        }
      },
      {data:'created_at',  name:'ub.created_at'},
      {data:'berkas_status', name:'ub.status', orderable:false, searchable:false},
      {data:'notes', name:'ub.notes', defaultContent:'-', render: function(d){ return d && d.trim() !== '' ? d : '-'; }},
      {data:'action', name:'action', orderable:false, searchable:false, className:'text-center'}
    ],
    language: { paginate: { next: "›", previous: "‹" } }
  });

  $('#btnFilter').on('click', () => table.ajax.reload());
  $('#btnReset').on('click', () => {
    $('#date_from').val(''); $('#date_to').val(''); table.ajax.reload();
  });

  // Reject: buka modal
  $(document).on('click', '.btn-reject', function(){
    const id    = $(this).data('id');
    const name  = $(this).data('name') || '';
    const notes = $(this).data('notes') || '';

    $('#reject_id').val(id);
    $('#reject_name').val(name);
    $('#reject_notes').val(notes);
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
  });

  // Submit reject via AJAX
  $('#rejectForm').on('submit', function(e){
    e.preventDefault();
    const id    = $('#reject_id').val();
    const notes = $('#reject_notes').val().trim();

    $.ajax({
      url: "{{ url('/admin/submissions') }}/" + id + "/status",
      method: "POST",
      data: { _token: "{{ csrf_token() }}", status: "rejected", notes: notes },
      success: function(){
        bootstrap.Modal.getInstance(document.getElementById('rejectModal')).hide();
        table.ajax.reload(null, false);
      },
      error: function(xhr){
        alert('Gagal memperbarui status: ' + (xhr.responseJSON?.message || 'unknown error'));
      }
    });
  });
});
</script>
@endpush
