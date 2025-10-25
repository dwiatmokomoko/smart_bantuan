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
      <div class="table-responsive">
        <table class="table table-striped" id="submissions_table">
          <thead>
            <tr>
              <th class="text-center">No</th>
              <th>Nama</th>
              <th>No HP</th>
              <th>Kelayakan</th>
              <th>Prob. Layak</th> {{-- aktifkan --}}
              <th>Status Berkas</th>
              <th>Created</th>
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
  $('#submissions_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('admin.submissions.data', [], false) }}",
    order: [[6, 'desc']], // kolom Created
    columns: [
      {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false, className:'text-center'},
      {data:'user_name',   name:'u.name'},
      {data:'no_hp',       name:'u.no_hp', defaultContent:'-'},
      {data:'kelayakan',   name:'dt.kelayakan'},
      {data:'prob_layak',  name:'dt.prob_layak'},   // << tampilkan
      {data:'berkas_status', name:'ub.status', orderable:false, searchable:false},
      {data:'created_at',  name:'ub.created_at'},
      {data:'action',      name:'action', orderable:false, searchable:false, className:'text-center'}
    ]
  });
});
</script>
@endpush
