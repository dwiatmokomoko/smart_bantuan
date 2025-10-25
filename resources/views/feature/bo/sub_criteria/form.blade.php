@extends('app.app')

@push('style')
  <link href="{{ asset('bo/css/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
  <div class="container-fluid">
    <div class="col-md-6 card mt-4">
      <div class="card-body">
        <div class="card-body table-responsive">
          <div class="row mb-3">
            <div class="col-md-12">
              <h5 class="card-title fw-semibold mb-4">{{ $data['form_status'] }}Sub Kriteria</h5>
            </div>
          </div>

          <form method="POST" action="{{ route('sub-criteria.store') }}">
            @csrf

            <div class="mb-3">
              <label class="form-label">Nama Kriteria</label>
              <select name="criteria_id" id="criteria_id" class="form-control select2" required>
                <option value="" disabled selected>Pilih Kriteria</option>
                @isset($data['criteria'])
                  @foreach ($data['criteria'] as $criteria)
                    <option value="{{ encrypt($criteria->id) }}"
                      @if(isset($data['record']) && $data['record']['criteria_id'] == $criteria->id) selected @endif>
                      {{ $criteria->name }}
                    </option>
                  @endforeach
                @endisset
              </select>
              @error('criteria_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label for="name" class="form-label">Nama Sub Kriteria</label>
              <input type="text" name="name" class="form-control" id="name"
                     placeholder="Nama Sub Kriteria"
                     value="{{ old('name', $data['record']['name'] ?? '') }}" required>
              @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- ID terenkripsi untuk update (jika ada) --}}
            <input type="hidden" name="id" value="{{ isset($data['record']) ? encrypt($data['record']['id']) : '' }}">

            <div class="mb-3">
              <label for="weight" class="form-label">Bobot</label>

              {{-- Dropdown bobot yang menyesuaikan kriteria --}}
              <select name="weight" id="weight" class="form-control" required>
                <option value="" disabled selected>Pilih Bobot</option>
                {{-- Akan diisi via JS sesuai kriteria yang dipilih --}}
              </select>
              @error('weight') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

              {{-- <div class="form-text mt-1">
                Nilai yang disimpan adalah <b>persentase</b> (misal 25, 33, 50, 67, 75, 100).
              </div> --}}
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
    $(function () {
      $('.select2').select2();

      // Mapping opsi bobot per kriteria (ID asli, bukan terenkripsi)
      const allowedWeights = {
        '1': [10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100],
        '3': [10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100],
        '2': [10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100],
        '4': [10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100],
        '5': [10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100]
      };

      const $criteria = $('#criteria_id');
      const $weight   = $('#weight');

      // helper untuk ambil id asli dari option terenkripsi (kita embed sebagai data-id saat server-side? — kalau tidak punya, kita fallback isi opsi umum)
      // Karena criteria_id di option adalah "encrypted", kita tidak bisa baca id asli dari sini.
      // Maka strategi: saat "edit", kita isi langsung opsi sesuai record; saat "create", kita tampilkan opsi umum lalu admin pilih salah satu.
      // Solusi yang praktis: isi semua opsi umum dulu, lalu saat ganti kriteria, kita ganti sesuai mapping PER ID yang dikenali.
      // Untuk itu kita butuh id asli; jika tidak tersedia di DOM, kita biarkan opsi umum 0..100.
      // ----
      // Lebih praktis: saat render edit, kita kirimkan 'record.criteria_id' & 'record.weight' di data-*.
      const recordCriteriaId = @json($data['record']['criteria_id'] ?? null);
      const recordWeight     = @json($data['record']['weight'] ?? null);

      function fillWeights(options, selectedValue = null) {
        $weight.empty();
        $weight.append(new Option('Pilih Bobot', '', true, false)).prop('disabled', false);

        (options || []).forEach(function (val) {
          const opt = new Option(val, val, false, selectedValue != null && Number(selectedValue) === Number(val));
          $weight.append(opt);
        });

        if (selectedValue && !$weight.find('option[value="'+selectedValue+'"]').length) {
          // jika nilai existing tidak ada di opsi, tambahkan agar tetap terlihat
          const extra = new Option(selectedValue , selectedValue, true, true);
          $weight.append(extra);
        }
      }

      // Jika halaman "edit": isi opsi sesuai criteria_id record
      if (recordCriteriaId) {
        const opts = allowedWeights[String(recordCriteriaId)] || [10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100];
        fillWeights(opts, recordWeight);
      } else {
        // halaman "create": isi opsi umum dulu (bisa diganti setelah pilih kriteria)
        fillWeights([10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100], null);
      }

      // Saat kriteria berubah, set opsi bobot sesuai mapping
      $criteria.on('change', function(){
        // Tidak bisa dapat id asli (karena terenkripsi). Solusi:
        // - Saat change, kita tampilkan opsi *umum* yang paling sering dipakai.
        // - Jika kamu ingin presisi per kriteria, kirimkan juga data-id asli sebagai data-attr di <option> server-side.
        //   Contoh: <option value="{{ encrypt($criteria->id) }}" data-real-id="{{ $criteria->id }}">...</option>
        //   Lalu baca: const realId = $('#criteria_id option:selected').data('real-id');
        // Di bawah ini contoh yang menggunakan data-real-id jika tersedia.

        const $opt   = $('#criteria_id option:selected');
        const realId = $opt.data('real-id'); // pastikan kamu menambahkan data-real-id di blade create()

        let opts;
        if (realId && allowedWeights[String(realId)]) {
          opts = allowedWeights[String(realId)];
        } else {
          // fallback opsi umum
          opts = [10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100];
        }
        fillWeights(opts, null);
      });
    });
  </script>
@endpush
