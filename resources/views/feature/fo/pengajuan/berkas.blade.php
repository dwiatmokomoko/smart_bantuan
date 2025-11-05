{{-- resources/views/feature/fo/pengajuan/files.blade.php --}}
@extends('app.app_fo')

@push('style')
    <style>
        /* --- Layout spacing --- */
        .files-row {
            /* jarak horizontal & vertical */
            row-gap: 2rem;
            /* gy-5 equivalent */
            column-gap: 1.25rem;
            /* gx-4 equivalent */
        }

        /* --- Card look & feel --- */
        .card-file {
            border-radius: 16px;
            border: 1px solid #eef2f7;
            box-shadow: 0 8px 24px rgba(16, 24, 40, .06);
            transition: transform .15s ease, box-shadow .15s ease;
            margin-bottom: .25rem;
            /* guard jika ada style lain menghapus gutter */
        }

        .card-file:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(16, 24, 40, .10);
        }

        .card-file .card-body {
            padding: 22px 22px 12px;
        }

        .card-file .card-footer {
            padding: 12px 22px 18px;
            background: #fff;
            border-top: 1px dashed #e9eef6;
        }

        /* --- Big circle icon on the left --- */
        .doc-chip {
            width: 44px;
            height: 44px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(16, 24, 40, .16);
            flex: 0 0 44px;
        }

        .chip-ktp {
            background: #6366f1;
        }

        /* indigo */
        .chip-kk {
            background: #06b6d4;
        }

        /* cyan   */
        .chip-sp {
            background: #f97316;
        }

        /* orange */
        .chip-sg {
            background: #22c55e;
        }

        /* green  */
        .chip-sk {
            background: #ef4444;
        }

        /* red    */

        /* judul file & meta */
        .file-title {
            font-weight: 700;
            margin: 0;
        }

        .file-meta {
            font-size: .9rem;
            color: #64748b;
            margin: 2px 0 0;
        }

        /* Buttons */
        .btn-file {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            border-radius: 9999px;
            padding: .5rem .9rem;
        }

        .btn-view {
            background: #e8f1ff;
            color: #1461d2;
            border: 1px solid #cfe0ff;
        }

        .btn-view:hover {
            background: #dcebff;
            color: #104fa9;
        }

        .btn-download {
            background: #e8f7ee;
            color: #1a7c3e;
            border: 1px solid #c7eed6;
        }

        .btn-download:hover {
            background: #dbf2e6;
            color: #155f31;
        }

        /* bantu rata tombol */
        .btn-wrap {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
        }

        /* Pusatkan lebar konten (lebih ramping & center) */
        .files-shell {
            max-width: 980px;
            /* 920–1000 enak; silakan ubah sesuai selera */
            margin: 0 auto;
            /* center */
        }

        @media (max-width: 991.98px) {
            .files-shell {
                max-width: 840px;
            }
        }

        /* Kurangi padding default section agar konten "naik" */
        .compact-pad {
            padding-top: 3rem;
            /* defaultnya spad biasanya tinggi */
            padding-bottom: 2rem;
        }

        /* Sedikit kurangi jarak breadcrumb ke section */
        .breadcrumb-section {
            margin-bottom: .75rem;
        }

        /* Baris kartu: center dan rapikan gap */
        .files-row {
            row-gap: 2rem;
            column-gap: 1.25rem;
            justify-content: center;
            /* <--- biar grid benar-benar di tengah */
        }
    </style>
@endpush

@section('content_fo')
    {{-- Breadcrumb --}}
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__option">
                        <a href="{{ route('fo.home.index') }}"><span class="fa fa-home"></span> Home</a>
                        <span>Detail Berkas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="hosting-section spad compact-pad">
        <div class="container files-shell"> <!-- <== sekarang lebarnya dipusatkan -->
            <div class="mb-3">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <h4 class="mb-2">Berkas Unggahan</h4>
            <p class="text-muted mb-4">
                Klik <strong>Lihat</strong> untuk membuka berkas di tab baru, atau <strong>Unduh</strong> untuk menyimpan.
            </p>
<br>
            @php
                /**
                 * Ekspektasi $files = [
                 *   ['label'=>'KTP', 'url'=>'/storage/...', 'download_url'=>null],
                 *   ...
                 * ]
                 * Jika 'download_url' tidak ada, akan pakai 'url' dengan atribut download.
                 */
                $map = [
                    'KTP' => ['chip' => 'chip-ktp'],
                    'KK' => ['chip' => 'chip-kk'],
                    'SPBMR' => ['chip' => 'chip-sp'], // Surat Perny. Belum Memiliki Rumah
                    'Slip Gaji / Surat Penghasilan' => ['chip' => 'chip-sg'],
                    'SKCK' => ['chip' => 'chip-sk'],
                ];
                // bantu tentukan chip class berdasar label
                $chipClass = function ($label) use ($map) {
                    if (isset($map[$label])) {
                        return $map[$label]['chip'];
                    }
                    // fallback berdasarkan kata kunci
                    $l = strtolower($label);
                    return str_contains($l, 'ktp')
                        ? 'chip-ktp'
                        : (str_contains($l, ' kk') || $l === 'kk'
                            ? 'chip-kk'
                            : (str_contains($l, 'slip') || str_contains($l, 'penghasilan')
                                ? 'chip-sg'
                                : (str_contains($l, 'skck')
                                    ? 'chip-sk'
                                    : 'chip-sp')));
                };
            @endphp

            @if (empty($files))
                <div class="alert alert-light border">
                    Tidak ada berkas untuk tiket ini.
                </div>
            @else
                <div class="row files-row">
                    @foreach ($files as $f)
                        @php
                            $label = $f['label'] ?? 'Berkas';
                            $url = $f['url'] ?? '#';
                            $dlUrl = $f['download_url'] ?? $url;
                            $chip = $chipClass($label);
                        @endphp

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card card-file h-100">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <div class="doc-chip {{ $chip }}">
                                        <i class="fa fa-file"></i>
                                    </div>
                                    <div>
                                        <p class="file-title">{{ $label }}</p>
                                        <p class="file-meta">JPG/PNG/PDF · Maks 5MB</p>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-between align-items-center">
                                    <div class="btn-wrap">
                                        <a href="{{ $url }}" target="_blank" rel="noopener"
                                            class="btn btn-sm btn-file btn-view">
                                            <i class="fa fa-eye"></i> Lihat
                                        </a>

                                        {{-- Jika ingin pakai atribut download langsung, gunakan tag <a download> --}}
                                        <a href="{{ $dlUrl }}" class="btn btn-sm btn-file btn-download" download>
                                            <i class="fa fa-download"></i> Unduh
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </section>
@endsection
