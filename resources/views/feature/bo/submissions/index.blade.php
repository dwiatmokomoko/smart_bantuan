{{-- resources/views/feature/bo/submissions/index.blade.php --}}
@extends('app.app')

@push('style')
<link rel="stylesheet" href="{{ asset('bo/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  .filters .form-control { max-width: 220px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="card mt-4">
    <div class="card-body">
      <h5 class="card-title fw-semibold mb-3">Daftar Pengajuan Rusunawa</h5>

      {{-- Filter tanggal + Sort --}}
      <div class="d-flex gap-2 align-items-end mb-3 filters">
        <div>
          <label class="form-label mb-1">Dari</label>
          <input type="date" id="date_from" class="form-control">
        </div>
        <div>
          <label class="form-label mb-1">Sampai</label>
          <input type="date" id="date_to" class="form-control">
        </div>
        <div>
          <label class="form-label mb-1">Urutkan</label>
          <select id="sort_prob" class="form-control">
            <option value="">— default —</option>
            <option value="desc">Nilai Kelayakan Tertinggi</option>
            <option value="asc">Nilai Kelayakan Terendah</option>
          </select>
        </div>
        <button id="btnFilter" class="btn btn-primary">Terapkan</button>
        <button id="btnReset" class="btn btn-outline-secondary">Reset</button>
      </div>

      <div class="table-responsive">
        <table class="table table-striped" id="submissions_table" width="100%">
          <thead>
            <tr>
              <th class="text-center">No</th>
              <th>Nama</th>
              <th>No HP</th>
              <th>Nilai Kelayakan</th>
              <th>Tanggal Pengajuan</th>
              <th>Status Berkas</th>
              <th>Keterangan</th> {{-- <- baru --}}
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

    </div>
  </div>
</div>
@endsection

@push('script')
<script src="{{ asset('bo/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('bo/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
(function(){
  const csrf = '{{ csrf_token() }}';

  // inisialisasi DataTable
  const table = $('#submissions_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "{{ route('admin.submissions.data', [], false) }}",
      data: function(d){
        d.date_from = $('#date_from').val() || '';
        d.date_to   = $('#date_to').val() || '';
      }
    },
    order: [[4, 'desc']], // default urut created_at desc
    columns: [
      {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false, className:'text-center'},
      {data:'user_name',   name:'u.name'},
      {data:'no_hp',       name:'u.no_hp', defaultContent:'-'},
      {data:'prob_layak',  name:'dt.prob_layak'},     // sortable numeric (server-side)
      {data:'created_at',  name:'ub.created_at'},
      {data:'berkas_status', name:'ub.status', orderable:false, searchable:false},
      {data:'notes',       name:'ub.notes', defaultContent:'-', render:(d)=> d ? d : '-'}, // kolom keterangan
      {data:'action',      name:'action', orderable:false, searchable:false, className:'text-center'}
    ],
    language: {
      paginate: { next: "›", previous: "‹" }
    }
  });

  // Filter tanggal
  $('#btnFilter').on('click', () => table.ajax.reload());
  $('#btnReset').on('click', () => {
    $('#date_from').val(''); $('#date_to').val(''); $('#sort_prob').val('');
    table.order([[4,'desc']]).draw();      // reset order
    table.ajax.reload();
  });

  // Sort by nilai kelayakan
  $('#sort_prob').on('change', function(){
    const v = $(this).val();
    if(!v) { table.order([[4,'desc']]).draw(); return; } // kembali ke created_at desc
    // kolom prob_layak index = 3
    table.order([[3, v]]).draw();
  });

  // Approve
  $(document).on('click', '.btn-approve', function(){
    const url = $(this).data('url');
    $.post({
      url: url,
      data: { _token: csrf, status: 'approved' },
      success: () => table.ajax.reload(null,false),
      error:   (xhr)=> alert('Gagal memperbarui status:\n' + (xhr.responseJSON?.message || xhr.statusText))
    });
  });

  // Reject (wajib isi notes/keterangan)
  $(document).on('click', '.btn-reject', function(){
    const url = $(this).data('url');
    const notes = prompt('Tulis keterangan penolakan (contoh: KTP buram / KK tidak sesuai dll.):');
    if(notes === null) return; // cancel
    if(notes.trim() === ''){
      alert('Keterangan wajib diisi saat menolak.');
      return;
    }
    $.post({
      url: url,
      data: { _token: csrf, status: 'rejected', notes: notes.trim() },
      success: () => table.ajax.reload(null,false),
      error:   (xhr)=> alert('Gagal memperbarui status:\n' + (xhr.responseJSON?.message || xhr.statusText))
    });
  });

  // Tombol WA (template otomatis)
  $(document).on('click', '.btn-wa', function(){
    const hp     = $(this).data('hp');
    const name   = $(this).data('name');
    const ticket = $(this).data('ticket') || '-';
    const status = $(this).data('status');            // pending / approved / rejected
    const notes  = $(this).data('notes') || '';

    let text = '';
    if(status === 'approved'){
      text =
`Halo, Bapak/Ibu ${name} 👋
Kami informasikan bahwa status pengajuan Anda telah disetujui untuk melanjutkan ke tahap wawancara calon penghuni Rusunawa.

Saat proses wawancara, silahkan membawa berkas-berkas berikut ini:
- Fotokopi KTP
- Fotokopi KK
- Fotokopi Surat Nikah/Akta cerai hiduop/ Akta kematian
- Surat Pernyataan Belum Memiliki Rumah
- Surat Pernyataan Penghasilan / Slip Gaji
- SKCK yang masih berlaku
- Pas Foto ukuran 4 x 6 masing-masing anggota keluarga

Untuk jadwal wawancara, akan kami informasikan lebih lanjut melalui pesan WhatsApp ini.
Terima kasih atas perhatian dan kerjasamanya 🙏

Salam,
Tim Pengelola Rusunawa Kota Yogyakarta
(Tiket: ${ticket})`;
    } else if(status === 'rejected'){
      const alasan = notes ? notes : 'alasan penolakan terlampir pada sistem';
      text =
`Halo, Bapak/Ibu ${name} 🙏
Kami informasikan bahwa status pengajuan Anda belum dapat disetujui.

Berdasarkan hasil verifikasi berkas, pengajuan belum memenuhi kriteria karena ${alasan}.

Terima kasih atas pengertian dan perhatiannya 🙏

Salam,
Tim Pengelola Rusunawa Kota Yogyakarta
(Tiket: ${ticket})`;
    } else {
      text =
`Halo ${name}, terima kasih telah mengajukan permohonan RUSUNAWA (Tiket ${ticket}).
Berkas Anda sudah kami terima dan saat ini sedang dalam proses verifikasi.
Kami akan menghubungi Anda kembali setelah proses selesai. Terima kasih 🙏`;
    }

    const url = 'https://wa.me/' + hp + '?text=' + encodeURIComponent(text);
    window.open(url, '_blank');
  });

})();
</script>
@endpush
