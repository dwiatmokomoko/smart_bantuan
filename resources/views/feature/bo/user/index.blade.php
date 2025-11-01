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
                            <h5 class="card-title fw-semibold mb-4">Daftar Pengguna</h5>
                        </div>
                        <div class="col-md-6">
                            <a class="d-flex float-end btn btn-outline-success" href="{{ route('user.create') }}"><span>
                                    <i class="ti ti-pencil-plus me-2"></i>
                                </span>
                                <span class="hide-menu">Tambah Pengguna</span></a>
                        </div>
                    </div>
                    <table class="table table-stripe user-table">
                        <thead>
                            <tr>
                                <th class="text-center w-1">No</th>
                                <th class="text-start">Nama</th>
                                <th class="text-start">Email</th>
                                <th class="text-start">Alamat</th>
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
            var table = $('.user-table').DataTable({
                language: {
                    paginate: {
                        next: "›",
                        previous: "‹"
                    }
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.data', [], false) }}", 
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [{
                        "targets": 0,
                        "className": "text-center align-middle text-sm font-weight-normal",
                        "width": "4%"
                    },
                    {
                        "targets": 1,
                        "className": "ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal",
                    },
                    {
                        "targets": 2,
                        "className": "ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal",
                    },
                    {
                        "targets": 3,
                        "className": "ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal",
                    }, {
                        "targets": 4,
                        "className": "align-middle text-sm font-weight-normal",
                    }
                ]
            });

            $(document).on('click', '#deleteRow', function(event) {
                var form = $(this).closest("form");
                var name = $(this).data("name");
                console.log($('.user-table tr.active'));
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
@endpush
