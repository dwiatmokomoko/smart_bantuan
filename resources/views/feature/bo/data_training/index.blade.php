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
            <h5 class="card-title fw-semibold mb-4">Daftar Data Latih</h5>
          </div>
        </div>

        <table class="table table-striped data-training-table" id="data_training_table">
          <thead>
            <tr>
              <th class="text-center w-1">No</th>
              <th class="text-start">Nama</th>
              <th class="text-start">C1</th>
              <th class="text-start">C2</th>
              <th class="text-start">C3</th>
              <th class="text-start">C4</th>
              <th class="text-start">C5</th>
              <th class="text-start">C6</th>
              <th class="text-start">Prob. Layak</th>
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
              <li>C1 : Pekerjaan</li>
              <li>C2 : Status Hubungan Dalam Keluarga</li>
              <li>C3 : Data Kependudukan Sinkron</li>
              <li>C4 : Adanya Anggota Keluarga Sudah Ditanggung Iuran BPJS</li>
              <li>C5 : Adanya Anggota Keluarga di luar keluarga inti</li>
              <li>C6 : Kependudukan Sesuai Wilayah PBI BPJS</li>
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
      $('#data_training_table').DataTable({
        language: {
          paginate: { next: "›", previous: "‹" }
        },
        processing: true,
        serverSide: true,
        ajax: "{{ route('data-trainings.data', [], false) }}",
        order: [[0, 'asc']],
        columns: [
          { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false, className:'text-center' },
          { data: 'name',               name: 'name' },
          { data: 'pekerjaan',          name: 'pekerjaan',          className:'text-center' },
          { data: 'status_hubungan_keluarga',  name: 'status_hubungan_keluarga',  className:'text-center' },
          { data: 'data_kependudukan_sinkron',  name: 'data_kependudukan_sinkron',  className:'text-center' },
          { data: 'anggota_keluarga_bpjs',     name: 'anggota_keluarga_bpjs',     className:'text-center' },
          { data: 'anggota_keluarga_luar',     name: 'anggota_keluarga_luar',     className:'text-center' },
          { data: 'kependudukan_wilayah_pbi',  name: 'kependudukan_wilayah_pbi',  className:'text-center' },
          {
            data: 'prob_layak',
            name: 'prob_layak',
            className: 'text-center',
            render: function (data) {
              if (data === null || data === undefined || data === '') return '-';
              const num = parseFloat(data);
              if (Number.isNaN(num)) return data;
              return num.toFixed(6); // tampilkan 6 desimal
            }
          },
        ]
      });
    });
  </script>
@endpush
