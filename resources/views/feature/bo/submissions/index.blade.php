{{-- resources/views/feature/bo/submissions/index.blade.php --}}
@extends('app.app')

@push('style')
<link rel="stylesheet" href="{{ asset('bo/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
<div class="container-fluid">
  <div class="card mt-4">
    <div class="card-body">
      <h5 class="card-title fw-semibold mb-4">Daftar Pengajuan Rusunawa</h5>

      {{-- Filter --}}
      <div class="row g-2 mb-3">
        <div class="col-md-3">
          <label class="form-label mb-1">Dari Tanggal</label>
          <input type="date" id="date_from" class="form-control">
        </div>
        <div class="col-md-3">
          <label class="form-label mb-1">Sampai Tanggal</label>
          <input type="date" id="date_to" class="form-control">
        </div>
        <div class="col-md-3">
          <label class="form-label mb-1">Urutkan Nilai Kelayakan</label>
          <select id="order_prob" class="form-select">
            <option value="">Default (tanggal)</option>
            <option value="desc">Tertinggi → Terendah</option>
            <option value="asc">Terendah → Tertinggi</option>
          </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button id="btnFilter" class="btn btn-outline-primary w-100"><i class="ti ti-filter"></i> Terapkan Filter</button>
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
              <th>Klasifikasi</th>
              <th>Tanggal Pengajuan</th>
              <th>Status</th>
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
        d.date_from = $('#date_from').val() || '';
        d.date_to   = $('#date_to').val() || '';
        d.order_prob= $('#order_prob').val() || '';
      }
    },
    order: [], // server sudah handle
    columns: [
      {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false, className:'text-center'},
      {data:'user_name',   name:'u.name'},
      {data:'no_hp',       name:'u.no_hp', defaultContent:'-'},
      {data:'prob_layak',  name:'dt.prob_layak'},
      {data:'kelas',       name:'kelas', orderable:false, searchable:false},
      {data:'created_at',  name:'ub.created_at'},
      {data:'berkas_status', name:'ub.status', orderable:false, searchable:false},
      {data:'notes_show',  name:'ub.notes', orderable:false, searchable:false},
      {data:'action',      name:'action', orderable:false, searchable:false, className:'text-center'}
    ],
    drawCallback: function(){
      // Handle klik tolak -> minta alasan
      $('.btn-reject').off('click').on('click', function(){
        const form = $(this).closest('form.frm-reject');
        const alasan = prompt('Silakan isi keterangan/ alasan penolakan:');
        if (alasan === null) return; // cancel
        form.find('input[name="notes"]').val(alasan.trim());
        form.trigger('submit');
      });
    }
  });

  $('#btnFilter').on('click', function(){
    table.ajax.reload();
  });
});
</script>
@endpush
