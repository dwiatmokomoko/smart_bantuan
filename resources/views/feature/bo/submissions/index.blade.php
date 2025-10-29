{{-- resources/views/feature/bo/submissions/index.blade.php --}}
@extends('app.app')

@push('style')
<link rel="stylesheet" href="{{ asset('bo/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('bo/css/confirmjs.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  .order-controls .form-check{ margin-right:1rem; }
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="card mt-4">
    <div class="card-body">
      <h5 class="card-title fw-semibold mb-3">Daftar Pengajuan Rusunawa</h5>

      {{-- Kontrol sorting ganda --}}
      <div class="order-controls d-flex align-items-center mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="ordProb" checked>
          <label class="form-check-label" for="ordProb">Nilai kelayakan tertinggi</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="ordFast" checked>
          <label class="form-check-label" for="ordFast">Pengajuan tercepat</label>
        </div>
        <button id="applyOrder" class="btn btn-sm btn-outline-primary ms-2">
          Terapkan Urutan
        </button>
      </div>

      <div class="table-responsive">
        <table class="table table-striped" id="submissions_table">
          <thead>
            <tr>
              <th class="text-center">No</th>
              <th>Nama</th>
              <th>No HP</th>
              <th>Nilai Kelayakan</th>
              <th>Tanggal Pengajuan</th>
              <th>Status Berkas</th>
              <th>Keterangan</th> {{-- notes --}}
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Modal Tolak (isi keterangan) --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="rejectForm" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tolak Pengajuan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="rejectUrl">
        <div class="mb-2">
          <label class="form-label">Alasan penolakan (keterangan)</label>
          <textarea id="rejectNotes" class="form-control" rows="3" placeholder="Contoh: KTP tidak sesuai, KK buram, dsb."></textarea>
        </div>
        <small class="text-muted d-block">Keterangan ini disimpan dan otomatis ikut dimasukkan ke pesan WhatsApp.</small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger">Tolak</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('script')
<script src="{{ asset('bo/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('bo/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('bo/js/confirmjs.min.js') }}"></script>
<script>
let dt;

function applyOrderingFromControls(){
  // indeks kolom: 0 No, 1 Nama, 2 HP, 3 Prob, 4 Created, 5 Status, 6 Notes, 7 Aksi
  const ords = [];
  if (document.getElementById('ordProb').checked) ords.push([3,'desc']);
  if (document.getElementById('ordFast').checked) ords.push([4,'asc']);
  if (ords.length === 0) ords.push([4,'desc']); // fallback
  dt.order(ords).draw();
}

$(function () {
  dt = $('#submissions_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('admin.submissions.data', [], false) }}",
    order: [[3, 'desc'], [4, 'asc']], // default: prob tertinggi, pengajuan tercepat
    columns: [
      {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false, className:'text-center'},
      {data:'user_name',   name:'u.name'},
      {data:'no_hp',       name:'u.no_hp', defaultContent:'-'},
      {data:'prob_layak',  name:'dt.prob_layak'},
      {data:'created_at',  name:'ub.created_at'},
      {data:'berkas_status', name:'ub.status', orderable:false, searchable:false},
      {data:'notes',       name:'ub.notes', defaultContent:'-'},
      {data:'action',      name:'action', orderable:false, searchable:false, className:'text-center'}
    ]
  });

  // Terapkan urutan dari kontrol
  $('#applyOrder').on('click', function(){
    applyOrderingFromControls();
  });

  // Approve (POST tanpa notes)
  $(document).on('click', '.btn-approve', function(){
    const url = $(this).data('url');
    fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ status: 'approved' })
    }).then(() => dt.ajax.reload(null, false));
  });

  // Reject -> buka modal
  let rejectBootstrapModal;
  $(document).on('click', '.btn-reject', function(){
    $('#rejectUrl').val($(this).data('url'));
    $('#rejectNotes').val('');
    rejectBootstrapModal = new bootstrap.Modal(document.getElementById('rejectModal'));
    rejectBootstrapModal.show();
  });

  // Submit reject (POST dgn notes)
  $('#rejectForm').on('submit', function(e){
    e.preventDefault();
    const url = $('#rejectUrl').val();
    const notes = $('#rejectNotes').val();
    fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ status: 'rejected', notes })
    })
    .then(()=> {
      rejectBootstrapModal.hide();
      dt.ajax.reload(null, false);
    });
  });

  // WhatsApp (buat pesan sesuai status + notes)
  $(document).on('click', '.btn-wa', function(){
    const hp     = $(this).data('hp');
    const name   = $(this).data('name');
    const ticket = $(this).data('ticket');
    const status = $(this).data('status');   // pending/approved/rejected
    const notes  = ($(this).data('notes')||'').trim();

    let text = "";
    if(status === 'approved'){
      text =
`Halo, Bapak/Ibu ${name} 👋
Kami informasikan bahwa status pengajuan Anda telah disetujui untuk melanjutkan ke tahap wawancara calon penghuni Rusunawa (Nomor Pendaftaran ${ticket}).

Saat proses wawancara, silahkan membawa berkas-berkas berikut ini:
- Fotokopi KTP
- Fotokopi KK
- Fotokopi Surat Nikah/Akta cerai hidup/Akta kematian
- Surat Pernyataan Belum Memiliki Rumah
- Surat Pernyataan Penghasilan / Slip Gaji
- SKCK yang masih berlaku
- Pas Foto ukuran 4 x 6 masing-masing anggota keluarga

Untuk jadwal wawancara, akan kami informasikan lebih lanjut melalui pesan WhatsApp ini.
Terima kasih atas perhatian dan kerjasamanya 🙏

Salam,
Tim Pengelola Rusunawa Kota Yogyakarta`;
    } else if(status === 'rejected'){
      const alasan = notes ? notes : '—';
      text =
`Halo, Bapak/Ibu ${name} 🙏
Kami informasikan bahwa status pengajuan Anda belum dapat disetujui (Nomor Pendaftaran ${ticket}).

Berdasarkan hasil verifikasi berkas, pengajuan belum memenuhi kriteria karena: ${alasan}.

Terima kasih atas pengertian dan perhatiannya 🙏

Salam,
Tim Pengelola Rusunawa Kota Yogyakarta`;
    } else {
      text =
`Halo, Bapak/Ibu ${name} 🙏
Terima kasih telah mengajukan permohonan RUSUNAWA (Nomor Pendaftaran ${ticket}).
Berkas Anda sudah kami terima dan saat ini sedang dalam proses verifikasi.
Kami akan menghubungi Anda kembali setelah proses selesai. Terima kasih 🙏`;
    }

    const url = 'https://wa.me/'+hp+'?text='+encodeURIComponent(text);
    window.open(url, '_blank');
  });
});
</script>
@endpush
