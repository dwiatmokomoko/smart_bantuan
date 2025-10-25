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
              <h5 class="card-title fw-semibold mb-4">Daftar Sub Kriteria</h5>
            </div>
            <div class="col-md-6">
              <a class="d-flex float-end btn btn-outline-success" href="{{ route('sub-criteria.create') }}">
                <span><i class="ti ti-pencil-plus me-2"></i></span>
                <span class="hide-menu">Tambah Sub Kriteria</span>
              </a>
            </div>
          </div>

          <table class="table table-stripe sub-criteria_table">
            <thead>
              <tr>
                <th class="text-center w-1">No</th>
                <th class="text-start">Kriteria</th>
                <th class="text-start">Sub Kriteria</th>
                <th class="text-start">Bobot</th>
                <th class="w-25 text-center">Action</th>
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

  <script type="text/javascript">
    $(function () {
      var table = $('.sub-criteria_table').DataTable({
        language: { paginate: { next: "›", previous: "‹" } },
        processing: true,
        serverSide: true,
        ajax: "{{ route('sub-criterias.data', [], false) }}", // relative URL
        order: [[1, 'asc'], [2, 'asc']],
        columns: [
          { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
          { data: 'criteria_name', name: 'criteria_name' },
          { data: 'name',          name: 'sub_criterias.name' },
          {
            data: 'weight',
            name: 'sub_criterias.weight',
            render: function (data) {
              // tampilkan apa adanya dari DB (persentase), tambahkan simbol % agar jelas
              return (data !== null && data !== undefined) ? (parseInt(data,10)) : '-';
            }
          },
          { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        columnDefs: [
          { targets: 0, className: "text-center align-middle text-sm", width: "4%" },
          { targets: 1, className: "ps-3 align-middle text-sm" },
          { targets: 2, className: "ps-3 align-middle text-sm" },
          { targets: 3, className: "ps-3 align-middle text-sm" },
          { targets: 4, className: "align-middle text-sm" },
        ]
      });

      $(document).on('click', '#deleteRow', function (e) {
        e.preventDefault();
        const form = $(this).closest("form");
        $.confirm({
          icon: 'fa fa-warning',
          title: 'Yakin hapus data',
          content: 'Sub kriteria ' + $(this).data('message').bold() + ' akan dihapus permanen',
          type: 'orange',
          buttons: {
            delete: {
              text: 'Hapus',
              btnClass: 'btn-red',
              action: function () { form.submit(); }
            },
            batal: function () {}
          }
        });
      });
    });
  </script>
@endpush
