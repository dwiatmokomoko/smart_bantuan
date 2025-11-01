@extends('app.app')
@push('style')
    <link href="{{asset('bo/css/select2.min.css')}}" rel="stylesheet" />
@endpush
@section('content')
    <div class="container-fluid">
        <div class="col-md-6 card mt-4">
            <div class="card-body">
                <div class="card-body table-responsive">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5 class="card-title fw-semibold mb-4">{{ $data['form_status'] }} Admin</h5>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('user.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama asli"
                                id="name" aria-describedby="nameHelp"
                                value="{{ isset($data['record']) ? $data['record']['name'] : '' }}">
                        </div>

                        <input type="hidden" name="id" class="form-control"
                            value="{{ isset($data['record']) ? encrypt($data['record']['id']) : '' }}">

                        <label for="weight" class="form-label">Nama Pengguna</label>
                        <div class="mb-3">
                            <input type="text" id="username" name="username" class="form-control"
                                placeholder="Nama pengguna" aria-label="weight"
                                aria-describedby="basic-addon1"
                                value="{{ isset($data['record']) ? $data['record']['username'] : '' }}">
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email"
                                id="email" aria-describedby="nameHelp"
                                value="{{ isset($data['record']) ? $data['record']['email'] : '' }}">
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Kata Sandi</label>
                            <input type="password" name="password" class="form-control" placeholder="kata kunci"
                                id="password" aria-describedby="nameHelp"
                                value="">
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Alamat</label>
                            <textarea class="form-control" name="address" rows="10" id="address">
                                {{ isset($data['record']) ? $data['record']['address'] : '' }}
                            </textarea>
                        </div>
                        <button class="float-end btn btn-primary mt-3 mb-0">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('bo/js/custom.min.js') }}"></script>
    <script src="{{ asset('bo/js/select2.min.js') }}"></script>
    <script>
        function handleChange(input) {
            if (input.value < 0) input.value = 0;
            if (input.value > 100) input.value = 100;
        }

        $(document).ready(function() {
            $('.select2').select2();
        });
        setInputFilter(document.getElementById("weight"), function(value) {
            return /^\d*\.?\d*$/.test(value);
        }, "Harus Berupa angka");
    </script>
@endpush
