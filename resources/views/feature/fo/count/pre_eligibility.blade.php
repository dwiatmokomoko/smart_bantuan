@extends('app.app_fo')

@section('content_fo')
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__option">
                        <a href="{{ url('/') }}"><span class="fa fa-home"></span> Home</a>
                        <span>Hitung Kelayakan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Pre-Eligibility Form Begin -->
    <div class="contact-form spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3>Pra-Kelayakan Program BPJS PBI</h3>
                    <p class="mb-4">Silakan isi pertanyaan berikut untuk mengetahui apakah Anda memenuhi syarat awal.</p>

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pre-eligibility.check') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label>Apakah Anda warga kota Yogyakarta?</label>
                                    <select name="is_warga_kota" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Apakah pekerjaan Anda PNS/TNI/Polri?</label>
                                    <select name="is_aparat" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Lanjut</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Pre-Eligibility Form End -->
@endsection
