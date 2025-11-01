{{-- resources/views/feature/bo/user/fo_index.blade.php --}}
@extends('app.app')

@push('style')
<link rel="stylesheet" href="{{ asset('bo/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('bo/css/confirmjs.min.css') }}">
@endpush

@section('content')
<div class="container-fluid">
  <div class="card mt-4">
    <div class="card-body">
      <div class="card-body table-responsive">
        <div class="row mb-5">
          <div class="col-md-6">
            <h5 class="card-title fw-semibold mb-4">Daftar Pengguna (User/Warga)</h5>
          </div>
        </div>

        <table class="table table-stripe fo-user-table">
          <thead>
            <tr>
              <th class="text-center w-1">No</th>
              <th class="text-start">Nama</th>
              <th class="text-start">Email</th>
              <th class="text-start">No HP</th>
              <th class="text-start">Alamat</th>
              <th class="text-start">Created</th>
              <th class="w-25 text-center">action</th>
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
<script src="{{ asset('bo/js/confirmjs.min.js') }}"></script>
<script>
$(function(){
  const table = $('.fo-user-table').DataTable({
    language: { paginate: { next:'›', previous:'‹' } },
    processing: true,
    serverSide: true,
    ajax: "{{ route('fo-users.data', [], false) }}",
    order: [[5,'desc']], // created desc
    columns: [
      {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
      {data:'name',   name:'name'},
      {data:'email',  name:'email'},
      {data:'no_hp',  name:'no_hp', defaultContent:'-'},
      {data:'alamat', name:'alamat'},
      {data:'created_at', name:'created_at'},
      {data:'action', name:'action', orderable:false, searchable:false},
    ],
    columnDefs: [
      {targets:0, className:'text-center align-middle text-sm', width:'4%'},
      {targets:[1,2,3,4,5], className:'align-middle text-sm'},
      {targets:6, className:'text-center align-middle text-sm'}
    ]
  });

  $(document).on('click','#deleteRow', function(e){
    e.preventDefault();
    const form = $(this).closest('form');
    $.confirm({
      icon:'fa fa-warning', title:'Yakin hapus data?',
      content: 'User ' + $(this).data('message').bold() + ' akan dihapus permanen.',
      type:'orange',
      buttons:{
        delete:{ text:'Hapus', btnClass:'btn-red', action:()=> form.submit() },
        batal: function(){}
      }
    });
  });
});
</script>
@endpush
