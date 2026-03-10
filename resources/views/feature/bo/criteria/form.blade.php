@extends('app.app')
@section('content')
    <div class="container-fluid">
        <div class="col-md-6 card mt-4">
            <div class="card-body">
                <div class="card-body table-responsive">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5 class="card-title fw-semibold mb-4">{{ $data['form_status'] }} Kriteria</h5>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('criteria.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kriteria</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Kriteria"
                                id="name" aria-describedby="nameHelp"
                                value="{{ isset($data['record']) ? $data['record']['name'] : '' }}">
                        </div>

                        <input type="hidden" name="id" class="form-control"
                            value="{{ isset($data['record']) ? encrypt($data['record']['id']) : '' }}">

                        <label for="weight" class="form-label">Bobot</label>
                        <div class="input-group mb-3">
                            <input type="number" id="weight" name="weight" class="form-control" 
                                placeholder="Masukkan bobot (0-100)" min="0" max="100"
                                value="{{ isset($data['record']) ? $data['record']['weight'] : '' }}"
                                onchange="handleChange(this)" required>
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
    <script>
        function handleChange(input) {
            if (input.value < 0) input.value = 0;
            if (input.value > 100) input.value = 100;
        }

        setInputFilter(document.getElementById("weight"), function(value) {
            return /^\d*\.?\d*$/.test(value);
        }, "Harus Berupa angka");
    </script>
@endpush
