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
                            <h5 class="card-title fw-semibold mb-4">Daftar Kriteria</h5>
                        </div>
                        <div class="col-md-6">
                            <a class="d-flex float-end btn btn-outline-success" href="{{ route('criteria.create') }}"><span>
                                    <i class="ti ti-pencil-plus me-2"></i>
                                </span>
                                <span class="hide-menu">Tambah Kriteria</span></a>
                        </div>
                    </div>
                    <table class="table table-stripe criteria_table">
                        <thead>
                            <tr>
                                <th class="text-center w-1">No</th>
                                <th class="text-start">Nama</th>
                                <th class="text-start">Bobot</th>
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

    <script type="text/javascript">
        $(function() {
            var table = $('.criteria_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('criterias.data', [], false) }}",
                order: [
                    [2, 'asc']
                ], // urut by name
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'weight',
                        name: 'weight',
                        render: function(data) {
                            return parseInt(data, 10);
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $(document).on('click', '#deleteRow', function(event) {
                var form = $(this).closest("form");
                var name = $(this).data("name");
                console.log($('.criteria_table tr.active'));
                event.preventDefault();
                $.confirm({
                    icon: 'fa fa-warning',
                    title: 'Yakin hapus data',
                    content: 'Kriteria ' + $(this).data('message').bold() +
                        ' akan di hapus secara permanen',
                    type: 'orange',
                    typeAnimated: true,
                    animationSpeed: 500,
                    closeAnimation: 'zoom',
                    closeIcon: true,
                    closeIconClass: 'fa fa-close',
                    draggable: true,
                    backgroundDismiss: false,
                    backgroundDismissAnimation: 'glow',
                    buttons: {
                        delete: {
                            text: 'Hapus',
                            btnClass: 'btn-red',
                            action: function() {
                                form.submit();
                            }
                        },
                        batal: function() {}
                    }
                });
            });
        });
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
@endpush
