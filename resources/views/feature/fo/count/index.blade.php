@extends('app.app_fo')

@section('content_fo')
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__option">
                        <a href="./index.html"><span class="fa fa-home"></span> Home</a>
                        <span>Lengkapi Data Pendaftaran</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Contact Form Begin -->
    <div class="contact-form spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3>Lengkapi Data Pendaftaran</h3>
                    <form action="{{ route('fo.count.predict') }}" method="POST">
                        @csrf <!-- Laravel CSRF Protection -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="services__item">
                                    <h5 class="h3-content">Data Diri</h5>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Calon Penerima Bantuan</label>
                                        <input type="text" class="form-control"
                                            value="{{ old('name', $user->name ?? '') }}" readonly>
                                        <input type="hidden" name="name" value="{{ old('name', $user->name ?? '') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nomor Induk Kependudukan</label>
                                        <input type="text" class="form-control"
                                            value="{{ old('nik', $user->nik ?? '') }}" readonly>
                                        <input type="hidden" name="nik" value="{{ old('nik', $user->nik ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="form-control" id="jenis_kelamin">

                                            <option value="Laki-Laki">Laki-Laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>

                                    {{-- 'penghasilan',
                                    'pekerjaan',
                                    'perkawinan',
                                    'tinnggal_bersama',
                                    'status_kependudukan',
                                    'status_kepemilikan_rumah' --}}

                                    <div class="mb-3">
                                        <label class="form-label">Pekerjaan </label>
                                        <select name="pekerjaan" class="form-control" id="pekerjaan">
                                            @foreach ($subCriterias->where('criteria_id', 2)->sortBy('weight') as $item)
                                                <option value="{{ $item->weight }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="services__item">
                                    <h5 class="h3-content">Data Pendukung</h5>
                                    <div class="mb-3">
                                        <label class="form-label">Penghasilan</label>
                                        <select name="penghasilan" class="form-control" id="penghasilan">
                                            @foreach ($subCriterias->where('criteria_id', 1)->sortBy('id') as $item)
                                                <option value="{{ $item->weight }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Perkawinan</label>
                                        <select name="perkawinan" class="form-control" id="perkawinan">
                                            @foreach ($subCriterias->where('criteria_id', 3)->sortBy('weight') as $item)
                                                <option value="{{ $item->weight }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Calon Penghuni</label>
                                        <select name="calon_penghuni" class="form-control" id="calon_penghuni">
                                            @foreach ($subCriterias->where('criteria_id', 4)->sortBy('name') as $item)
                                                <option value="{{ $item->weight }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status Penempatan</label>
                                        <select name="status_penempatan" class="form-control" id="status_penempatan">
                                            @foreach ($subCriterias->where('criteria_id', 5)->sortBy('weight') as $item)
                                                <option value="{{ $item->weight }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <button type="submit" class="float-end btn btn-primary mt-3 mb-0" color="#28c490">Lanjut</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Form End -->
@endsection
