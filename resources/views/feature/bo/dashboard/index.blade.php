@extends('app.app')

@section('content')
    <!--  Header End -->
    <div class="container-fluid">
        <div class="card mt-4 text-center">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Selamat Datang</h5>

                {{-- Menampilkan logo --}}
                <img src="{{ asset('fo/img/silayak-logo2.png') }}" class="rounded-circle" alt="Logo Silayak" style="max-width: 700px; height: auto;">

                <p class="mt-3 mb-0">Sistem Penilaian Kelayakan PBI BPJS</p>
            </div>
        </div>
    </div>
@endsection
