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
                                                <td class="hosting__feature--item">Pekerjaan</td>
                                                <td class="w-50 text-left pl-4">{{ $input_label['pekerjaan'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="hosting__feature--item">Status Hubungan Dalam Keluarga</td>
                                                <td class="w-50 text-left pl-4">{{ $input_label['status_hubungan_keluarga'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="hosting__feature--item">Data Kependudukan Sinkron</td>
                                                <td class="w-50 text-left pl-4">{{ $input_label['data_kependudukan_sinkron'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="hosting__feature--item">Adanya Anggota Keluarga Sudah Ditanggung Iuran BPJS</td>
                                                <td class="w-50 text-left pl-4">{{ $input_label['anggota_keluarga_bpjs'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="hosting__feature--item">Adanya Anggota Keluarga di luar keluarga inti</td>
                                                <td class="w-50 text-left pl-4">{{ $input_label['anggota_keluarga_luar'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="hosting__feature--item">Kependudukan Sesuai Wilayah PBI BPJS</td>
                                                <td class="w-50 text-left pl-4">{{ $input_label['kependudukan_wilayah_pbi'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="hosting__feature--item font-weight-bold">Nilai Kelayakan</td>
                                                <td class="w-50 text-left pl-4 font-weight-bold">{{ number_format($prob_layak, 4, '.', '') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="hosting__feature--item font-weight-bold">Klasifikasi</td>
                                                <td class="w-50 text-left pl-4 font-weight-bold">
                                                    @if($prob_layak < 0.50)
                                                        <span class="badge badge-danger">Tidak Berhak Menerima PBI BPJS</span>
                                                    @elseif($prob_layak >= 0.50 && $prob_layak <= 0.75)
                                                        <span class="badge badge-warning">Bisa Diupayakan Menerima PBI BPJS dengan Penyesuaian Persyaratan</span>
                                                    @else
                                                        <span class="badge badge-success">Berhak Menerima PBI BPJS</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4 d-flex gap-2">
                                    <a href="{{ route('fo.count.index') }}" class="btn btn-secondary">Kembali</a>

                                    @if ($prob_layak >= 0.50)
                                        <a href="{{ route('fo.berkas.create',  ['ticket' => $ticket]) }}"
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
