@extends('app.app')

@push('style')
  <link rel="stylesheet" href="{{ asset('bo/css/dataTables.bootstrap5.min.css') }}">
@endpush

@section('content')
<div class="container-fluid">
  <div class="card mt-4">
    <div class="card-body">
      <div class="card-body table-responsive">
        <div class="row mb-5">
          <div class="col-md-6">
            <h5 class="card-title fw-semibold mb-4">Daftar Data Uji</h5>
          </div>
        </div>

        <table class="table table-striped" id="data_testing_table">
          <thead>
            <tr>
              <th class="text-center w-1">No</th>
              <th class="text-start">Nama</th>
              <th class="text-center">K1</th>
              <th class="text-center">K2</th>
              <th class="text-center">K3</th>
              <th class="text-center">K4</th>
              <th class="text-center">K5</th>
              <th class="text-center">Prob. Layak</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

      </div>

      <div class="card-footer">
        <div class="row">
          <div class="col-md-8">
            <h5>Keterangan :</h5>
            <ul class="mb-0">
              <li>K1 : Penghasilan</li>
              <li>K2 : Pekerjaan</li>
              <li>K3 : Status Penempatan</li>
              <li>K4 : Calon Penghuni</li>
              <li>K5 : Perkawinan</li>
            </ul>
          </div>
        </div>
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
      $('#data_testing_table').DataTable({
        language: { paginate: { next: "›", previous: "‹" }},
        processing: true,
        serverSide: true,
        ajax: "{{ route('data-testings.data', [], false) }}",
        order: [[0, 'asc']],
        columns: [
          { data: 'DT_RowIndex',         name: 'DT_RowIndex', orderable:false, searchable:false, className:'text-center' },
          { data: 'name',                name: 'name' },
          { data: 'penghasilan',         name: 'penghasilan',        className:'text-center' },
          { data: 'pekerjaan',           name: 'pekerjaan',          className:'text-center' },
          { data: 'status_penempatan',   name: 'status_penempatan',  className:'text-center' },
          { data: 'calon_penghuni',      name: 'calon_penghuni',     className:'text-center' },
          { data: 'perkawinan',          name: 'perkawinan',         className:'text-center' },
          {
            data: 'prob_layak',
            name: 'prob_layak',
            className: 'text-center',
            render: function (data) {
              if (data === null || data === undefined || data === '') return '-';
              const num = parseFloat(data);
              if (Number.isNaN(num)) return data;
              return num.toFixed(6);
            }
          },
        ]
      });
    });
  </script>
@endpush
