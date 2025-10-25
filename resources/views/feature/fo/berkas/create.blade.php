@extends('app.app_fo')

@section('content_fo')
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__option">
                        <a href="{{ route('fo.home.index') }}"><span class="fa fa-home"></span> Home</a>
                        <span>Upload Berkas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <section class="contact-form spad">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <h3 class="mb-3">Upload Berkas Persyaratan</h3>
                    <p class="text-muted mb-4">
                        Unggah berkas berikut dalam format <strong>JPG/PNG/PDF</strong>. Maksimal 5MB per berkas.
                    </p>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <div class="mb-2"><strong>Terjadi kesalahan:</strong></div>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST"
                        action="{{ route('fo.berkas.store', request('ticket') ? ['ticket' => request('ticket')] : []) }}"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- jika kamu ingin mengikat ke ticket --}}
                        <input type="hidden" name="ticket" value="{{ request('ticket') ?? session('last_ticket') }}">

                        <div class="mb-3">
                            <label class="form-label">KTP <span class="text-danger">*</span></label>
                            <input type="file" name="ktp" class="form-control" accept="image/*,application/pdf"
                                required>
                            <small class="text-muted">Format: JPG/PNG/PDF, maks 5MB.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">KK <span class="text-danger">*</span></label>
                            <input type="file" name="kk" class="form-control" accept="image/*,application/pdf"
                                required>
                            <small class="text-muted">Format: JPG/PNG/PDF, maks 5MB.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Surat Pernyataan Belum Memiliki Rumah <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="surat_belum_memiliki_rumah" class="form-control"
                                accept="image/*,application/pdf" required>
                            <small class="text-muted">Format: JPG/PNG/PDF, maks 5MB.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Slip Gaji / Surat Pernyataan Penghasilan <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="slip_gaji" class="form-control" accept="image/*,application/pdf"
                                required>
                            <small class="text-muted">Format: JPG/PNG/PDF, maks 5MB.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">SKCK <span class="text-danger">*</span></label>
                            <input type="file" name="skck" class="form-control" accept="image/*,application/pdf"
                                required>
                            <small class="text-muted">Format: JPG/PNG/PDF, maks 5MB.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('fo.home.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Kirim Berkas</button>
                        </div>
                    </form>

                    <hr class="my-5">

                    <p class="text-muted">
                        Pastikan setiap berkas jelas terbaca. Jika berupa foto, usahakan tidak buram dan semua sudut dokumen
                        terlihat.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
