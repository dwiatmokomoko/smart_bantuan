{{-- resources/views/feature/bo/submissions/index.blade.php --}}
@extends('app.app')

@push('style')
<link rel="stylesheet" href="{{ asset('bo/css/dataTables.bootstrap5.min.css') }}">
<style>
  .btn-compact { padding: .25rem .5rem; font-size: .825rem; }
  .w-actions { width: 200px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="card mt-4">
    <div class="card-body">
      <h5 class="card-title fw-semibold mb-4">Daftar Pengajuan Rusunawa</h5>
      <div class="table-responsive">
        <table class="table table-striped" id="submissions_table">
          <thead>
            <tr>
              <th class="text-center">No</th>
              <th>Nama</th>
              <th>No HP</th>
              <th>Kelayakan</th>
              <th>Prob. Layak</th>
              <th>Status Berkas</th>
              <th>Created</th>
              <th class="text-center w-actions">Aksi</th>
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
  // Format nomor untuk WhatsApp
  function waNumber(raw) {
    if (!raw) return null;
    let n = (''+raw).replace(/[^\d]/g,''); // keep digits only
    if (n.startsWith('0')) n = '62' + n.slice(1);
    else if (n.startsWith('62')) n = n;
    else if (n.startsWith('8')) n = '62' + n;
    return n;
  }

  // Template pesan berdasarkan status
  function buildWaMessage(status, name, ticket) {
    const nama = name || 'Bapak/Ibu';
    const t = ticket ? ` (Tiket: ${ticket})` : '';
    if (status === 'approved') {
      return `Assalamu’alaikum/Salam, Bapak/Ibu ${nama}.${t}\n\n` +
             `Pengajuan Rusunawa Anda telah *DISETUJUI*. ` +
             `Tim kami akan menghubungi Anda untuk tahap berikutnya. ` +
             `Mohon siapkan dokumen asli saat verifikasi lanjutan. Terima kasih.`;
    } else if (status === 'rejected') {
      return `Salam, Bapak/Ibu ${nama}.${t}\n\n` +
             `Terima kasih atas pengajuan Rusunawa yang telah Anda sampaikan. ` +
             `Setelah peninjauan, mohon maaf pengajuan Anda *belum dapat kami setujui* saat ini. ` +
             `Silakan periksa kembali persyaratan dan ajukan kembali bila memungkinkan. Terima kasih.`;
    } else {
      return `Halo, Bapak/Ibu ${nama}.${t}\n\n` +
             `Pengajuan Anda sedang *menunggu verifikasi*. ` +
             `Kami akan kabari kembali setelah proses pemeriksaan selesai. Terima kasih.`;
    }
  }

  const table = $('#submissions_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('admin.submissions.data', [], false) }}",
    order: [[5, 'desc']], // created desc
    columns: [
      {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false, className:'text-center'},
      {data: 'user_name',   name: 'u.name'},
      {data: 'no_hp',       name: 'u.no_hp', defaultContent: '-', render: (d)=> d || '-'},
      {data: 'kelayakan',   name: 'dt.kelayakan', defaultContent: '-'},
      {data: 'prob_layak',  name: 'dt.prob_layak'},
      {data: 'berkas_status', name:'ub.status', orderable:false, searchable:false},
      {data: 'created_at',  name: 'ub.created_at'},
      {
        data: null,
        name: 'action',
        orderable:false,
        searchable:false,
        className:'text-center',
        render: function(row){
          const showUrl = "{{ route('admin.submissions.show', ':id') }}".replace(':id', row.ub_id);
          const hp = row.no_hp || '';
          const wNum = waNumber(hp);
          const defaultMsg = 'Halo ' + (row.user_name||'') + ', terkait pengajuan Rusunawa'+ (row.ticket? ' (tiket: '+row.ticket+')' : '') + '.';
          const defaultWa = wNum ? ('https://wa.me/' + wNum + '?text=' + encodeURIComponent(defaultMsg)) : null;

          return `
            <div class="d-flex gap-1 justify-content-center">
              <a href="${showUrl}" class="btn btn-sm btn-outline-primary btn-compact" title="Detail">
                <i class="ti ti-eye"></i>
              </a>

              <div class="btn-group">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle btn-compact" data-bs-toggle="dropdown" aria-expanded="false" title="Ubah Status">
                  Verifikasi
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item js-set-status"
                         href="#"
                         data-id="${row.ub_id}"
                         data-status="approved"
                         data-name="${(row.user_name||'').replace(/"/g,'&quot;')}"
                         data-ticket="${row.ticket||''}"
                         data-hp="${(row.no_hp||'').replace(/"/g,'&quot;')}"
                  >Setujui</a></li>
                  <li><a class="dropdown-item js-set-status"
                         href="#"
                         data-id="${row.ub_id}"
                         data-status="rejected"
                         data-name="${(row.user_name||'').replace(/"/g,'&quot;')}"
                         data-ticket="${row.ticket||''}"
                         data-hp="${(row.no_hp||'').replace(/"/g,'&quot;')}"
                  >Tolak</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item js-set-status"
                         href="#"
                         data-id="${row.ub_id}"
                         data-status="pending"
                         data-name="${(row.user_name||'').replace(/"/g,'&quot;')}"
                         data-ticket="${row.ticket||''}"
                         data-hp="${(row.no_hp||'').replace(/"/g,'&quot;')}"
                  >Tandai Pending</a></li>
                </ul>
              </div>

              ${defaultWa
                ? `<a href="${defaultWa}" target="_blank" class="btn btn-sm btn-success btn-compact" title="Hubungi via WhatsApp">
                     <i class="ti ti-brand-whatsapp"></i>
                   </a>`
                : `<button class="btn btn-sm btn-secondary btn-compact" title="Nomor tidak valid" disabled>
                     <i class="ti ti-brand-whatsapp"></i>
                   </button>`}
            </div>
          `;
        }
      }
    ]
  });

  // Handle set status + kirim WA sesuai status
  $(document).on('click', '.js-set-status', function(e){
    e.preventDefault();
    const id     = $(this).data('id');
    const status = $(this).data('status');
    const name   = $(this).data('name') || '';
    const ticket = $(this).data('ticket') || '';
    const hp     = $(this).data('hp') || '';
    if (!id || !status) return;

    if(!confirm('Ubah status berkas menjadi "'+status+'"?')) return;

    $.ajax({
      url: "{{ route('admin.submissions.updateStatus', ':id') }}".replace(':id', id),
      method: 'POST',
      data: { _token: '{{ csrf_token() }}', status },
      success: function(res){
        alert(res.message || 'Status berhasil diperbarui.');
        table.ajax.reload(null,false);

        // Jika approved/rejected → tawarkan WA dengan pesan sesuai status
        if (status === 'approved' || status === 'rejected') {
          const num = waNumber(hp);
          if (num) {
            const text = buildWaMessage(status, name, ticket);
            const link = 'https://wa.me/' + num + '?text=' + encodeURIComponent(text);
            // Buka WA
            window.open(link, '_blank');
          } else {
            console.warn('Nomor HP tidak valid untuk WhatsApp.');
          }
        }
      },
      error: function(xhr){
        const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal memperbarui status.';
        alert(msg);
      }
    });
  });

})();
</script>
@endpush
