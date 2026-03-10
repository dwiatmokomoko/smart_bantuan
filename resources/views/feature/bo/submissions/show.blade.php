@extends('app.app')

@push('style')
    <style>
        .file-thumb {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 10px;
            height: 260px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .file-thumb .preview {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 6px;
            background: #fafafa;
        }

        .file-thumb img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .file-thumb iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }

        /* Kartu kriteria */
        .criteria-badge {
            font-weight: 700
        }

        .table-sm td,
        .table-sm th {
            vertical-align: top
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
            <h4 class="fw-semibold mb-0">Detail Pengajuan</h4>
            <a href="{{ route('admin.submissions.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="row g-3">
            <!-- KIRI: Info + Hasil + Kriteria -->
            <div class="col-lg-5">
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Informasi Pemohon</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tr>
                                    <th class="w-25">Nama</th>
                                    <td>{{ $rec->user_name }}</td>
                                </tr>
                                <tr>
                                    <th>NIK</th>
                                    <td>{{ $rec->nik }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $rec->email }}</td>
                                </tr>
                                <tr>
                                    <th>No. HP</th>
                                    <td>{{ $rec->no_hp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Kode Pendaftaran</th>
                                    <td>{{ $rec->ticket ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Status Berkas</th>
                                    <td>{!! $statusBadge !!}</td>
                                </tr>
                                <tr>
                                    <th>Pengajuan</th>
                                    <td>{{ $rec->ub_created_at }}</td>
                                </tr>
                            </table>
                        </div>

                        <hr>
                        <h6 class="fw-semibold mb-3">Hasil Perhitungan</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tr>
                                    <th class="w-25">Klasifikasi</th>
                                    <td>{!! $klasifikasiBadge !!}</td>
                                </tr>
                                <tr>
                                    <th>Probabilitas Layak</th>
                                    <td>{{ is_null($rec->prob_layak) ? '-' : number_format((float) $rec->prob_layak, 6, '.', '') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Waktu Hitung</th>
                                    <td>{{ $rec->dt_created_at ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Kriteria & Sub-Kriteria yang dipilih --}}
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Data Pendukung</h6>

                        @if (!$labels && !$hasAnyRaw)
                            <div class="text-muted">-</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <th class="w-35">Pekerjaan</th>
                                            <td>
                                                <div>{{ $labels['pekerjaan'] ?? '—' }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status Hubungan Dalam Keluarga</th>
                                            <td>
                                                <div>{{ $labels['status_hubungan_keluarga'] ?? '—' }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Data Kependudukan Sinkron</th>
                                            <td>
                                                <div>{{ $labels['data_kependudukan_sinkron'] ?? '—' }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Adanya Anggota Keluarga Sudah Ditanggung Iuran BPJS</th>
                                            <td>
                                                <div>{{ $labels['anggota_keluarga_bpjs'] ?? '—' }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Adanya Anggota Keluarga di luar keluarga inti</th>
                                            <td>
                                                <div>{{ $labels['anggota_keluarga_luar'] ?? '—' }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Kependudukan Sesuai Wilayah PBI BPJS</th>
                                            <td>
                                                <div>{{ $labels['kependudukan_wilayah_pbi'] ?? '—' }}</div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- KANAN: Berkas -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Berkas Unggahan</h6>

                        <div class="row g-3">
                            @foreach ($files as $f)
                                @php
                                    $path = $f['path'];
                                    $url = $path ? asset('storage/' . $path) : null;
                                    $ext = $path ? strtolower(pathinfo($path, PATHINFO_EXTENSION)) : '';
                                    $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);
                                    $isPdf = $ext === 'pdf';
                                @endphp

                                <div class="col-md-6">
                                    <div class="file-thumb">
                                        <div class="small text-muted">{{ $f['label'] }}</div>
                                        <div class="preview">
                                            @if (!$url)
                                                <div class="text-muted">Tidak ada file</div>
                                            @else
                                                @if ($isImg)
                                                    <img src="{{ $url }}" alt="{{ $f['label'] }}">
                                                @elseif ($isPdf)
                                                    <iframe src="{{ $url }}"></iframe>
                                                @else
                                                    <div class="text-center text-muted">
                                                        Format: {{ strtoupper($ext) }}<br>
                                                        <a href="{{ $url }}" target="_blank">Buka/Unduh</a>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        @if ($url)
                                            <div>
                                                <a href="{{ $url }}" class="btn btn-sm btn-outline-primary w-100"
                                                    target="_blank">
                                                    <i class="ti ti-download"></i> Unduh
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
