@extends('app.app_fo')

@section('content_fo')
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__option">
                        <a href="./index.html"><span class="fa fa-home"></span> Home</a>
                        <span>Hasil Kelayakan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Result Section -->
    <section class="hosting-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h3>Hasil Kelayakan</h3>
                    </div>
                    <div class="hosting__text mb-5">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="hosting__feature__table">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="hosting__feature--item">Nama</td>
                                                <td class="w-50 text-left pl-4">{{ $data_input['name'] }}</td>
                                            </tr>
                                            <tr>
                                                <td class="hosting__feature--item">NIK</td>
                                                <td class="w-50 text-left pl-4">{{ $data_input['nik'] }}</td>
                                            </tr>
                                            <tr>
                                                <td class="hosting__feature--item">Jenis Kelamin</td>
                                                <td class="w-50 text-left pl-4">{{ $data_input['jenis_kelamin'] }}</td>
                                            </tr>
                                            <tr>
                                                <td class="hosting__feature--item">Penghasilan</td>
                                                <td class="w-50 text-left pl-4">{{ $input_label['penghasilan'] }}</td>
                                            </tr>

                                            <tr>
                                                <td class="hosting__feature--item">Pekerjaan</td>
                                                <td class="w-50 text-left pl-4">{{ $input_label['pekerjaan'] }}</td>
                                            </tr>
                                            <tr>
                                                <td class="hosting__feature--item">Status Perkawinan</td>
                                                <td class="w-50 text-left pl-4">{{ $input_label['perkawinan'] }}</td>
                                            </tr>
                                            <tr>
                                                <td class="hosting__feature--item">Calon Penghuni</td>
                                                <td class="w-50 text-left pl-4">{{ $input_label['calon_penghuni'] }}</td>
                                            </tr>
                                            <tr>
                                                <td class="hosting__feature--item">Status Penempatan</td>
                                                <td class="w-50 text-left pl-4">{{ $input_label['status_penempatan'] }}</td>
                                            </tr>
                                            <tr>
                                                <td class="hosting__feature--item font-weight-bold">Hasil Rekomendasi</td>
                                                <td
                                                    class="w-50 text-left pl-4 font-weight-bold {{ Str::lower($keputusan) == 'layak' ? 'text-success' : 'text-danger' }}">
                                                    {{ Str::upper($keputusan) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4 d-flex gap-2">
                                    <a href="{{ route('fo.count.index') }}" class="btn btn-secondary">Kembali</a>

                                    @if (Str::lower($keputusan) === 'layak')
                                        <a href="{{ route('fo.berkas.create', isset($ticket) ? ['ticket' => $ticket] : []) }}"
                                            class="btn btn-primary">
                                            Upload Berkas
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
