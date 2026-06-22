@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-12">
      <h1 class="mb-1 fw-bold">Kelola Wawancara</h1>
      <p class="mb-0 text-muted">Atur jadwal wawancara dan input hasil untuk calon mahasiswa pada jalur yang menggunakan tahapan wawancara.</p>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-circle fs-4 me-2"></i>
      {{ $errors->first() }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm overflow-hidden">
    <div class="card-body p-4">
      <!-- Scoped horizontal scroll wrapper for table only -->
      <div style="overflow-x: auto; max-width: 100%;">
        <table class="table table-hover align-middle mb-0" style="min-width: 1100px;">
          <thead class="bg-light">
            <tr>
              <th class="whitespace-nowrap px-3 py-3" style="width: 50px;">No</th>
              <th class="whitespace-nowrap px-3 py-3">No. Pendaftaran</th>
              <th class="whitespace-nowrap px-3 py-3">Nama Mahasiswa</th>
              <th class="whitespace-nowrap px-3 py-3">Jalur</th>
              <th class="whitespace-nowrap px-3 py-3">Jadwal Wawancara</th>
              <th class="whitespace-nowrap px-3 py-3">Pewawancara</th>
              <th class="whitespace-nowrap px-3 py-3">Status</th>
              <th class="whitespace-nowrap px-3 py-3" style="width: 210px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($registrations as $index => $reg)
              @php $w = $reg->wawancara; @endphp
              <tr>
                <td class="px-3 py-3">{{ ($registrations->currentPage() - 1) * $registrations->perPage() + $index + 1 }}</td>
                <td class="fw-semibold px-3 py-3 whitespace-nowrap">REG-{{ str_pad($reg->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td class="px-3 py-3 whitespace-nowrap">
                  <div class="d-flex align-items-center gap-2">
                    <img src="{{ optional($reg->user)->avatar_url ?? asset('assets/images/avatar/avatar-1.jpg') }}" class="rounded-circle" width="32" height="32" alt="">
                    <div>
                      <span class="d-block fw-semibold text-truncate" style="max-width: 180px;">{{ $reg->nama_lengkap }}</span>
                      @if($reg->user)
                        <small class="text-muted text-truncate" style="max-width: 180px;">{{ $reg->user->email }}</small>
                      @else
                        <small class="text-muted">—</small>
                      @endif
                    </div>
                  </div>
                </td>
                <td class="px-3 py-3 whitespace-nowrap">
                  <span class="badge bg-{{ $reg->registrationPath->color ?? 'secondary' }}-subtle text-{{ $reg->registrationPath->color ?? 'secondary' }} px-3 py-2 text-truncate" style="max-width: 150px;">
                    {{ $reg->registrationPath->name }}
                  </span>
                </td>
                <td class="px-3 py-3 whitespace-nowrap">
                  @if($w && $w->tanggal_wawancara)
                    <div class="d-flex align-items-center gap-1">
                      <i class="ti ti-calendar-event text-muted" style="font-size: 0.85rem;"></i>
                      <span>{{ $w->tanggal_wawancara->format('d/m/Y') }}</span>
                      @if($w->jam_wawancara)
                        <span class="text-muted mx-1">|</span>
                        <i class="ti ti-clock text-muted" style="font-size: 0.85rem;"></i>
                        <span>{{ $w->jam_wawancara }}</span>
                      @endif
                    </div>
                    @if($w->lokasi_wawancara)
                      <div class="mt-1">
                        <small class="text-muted">{{ $w->lokasi_wawancara }}</small>
                      </div>
                    @endif
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td class="px-3 py-3 whitespace-nowrap">
                  @if($w && $w->nama_pewawancara)
                    <span class="text-truncate" style="max-width: 160px; display: inline-block;">{{ $w->nama_pewawancara }}</span>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td class="px-3 py-3 whitespace-nowrap">
                  @if($w)
                    @if($w->status_wawancara === 'Belum Wawancara')
                      <span class="badge bg-warning-subtle text-warning px-3 py-2">Belum Wawancara</span>
                    @elseif($w->status_wawancara === 'Lolos')
                      <span class="badge bg-success-subtle text-success px-3 py-2">Lolos</span>
                    @else
                      <span class="badge bg-danger-subtle text-danger px-3 py-2">Tidak Lolos</span>
                    @endif
                  @else
                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2">Belum dijadwalkan</span>
                  @endif
                </td>
                <td class="px-3 py-3 whitespace-nowrap">
                  <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm py-2 btn-white d-inline-flex align-items-center gap-1"
                            data-bs-toggle="modal" data-bs-target="#jadwalModal"
                            data-pendaftaran-id="{{ $reg->id }}"
                            data-nama="{{ $reg->nama_lengkap }}"
                            data-tanggal="{{ $w?->tanggal_wawancara?->format('Y-m-d') ?? '' }}"
                            data-jam="{{ $w?->jam_wawancara ?? '' }}"
                            data-lokasi="{{ $w?->lokasi_wawancara ?? '' }}"
                            data-nama-pewawancara="{{ $w?->nama_pewawancara ?? '' }}">
                      <i class="ti ti-calendar-stats"></i> Jadwal
                    </button>
                    <button type="button" class="btn btn-sm py-2 btn-white d-inline-flex align-items-center gap-1"
                            data-bs-toggle="modal" data-bs-target="#hasilModal"
                            data-pendaftaran-id="{{ $reg->id }}"
                            data-nama="{{ $reg->nama_lengkap }}"
                            data-status="{{ $w?->status_wawancara ?? '' }}"
                            data-catatan="{{ $w?->catatan_pewawancara ?? '' }}">
                      <i class="ti ti-checklist"></i> Hasil
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center py-5">
                  <i class="ti ti-users text-muted" style="font-size: 3rem;"></i>
                  <p class="mt-3 mb-0 text-muted">Belum ada pendaftar pada jalur yang menggunakan wawancara.</p>
                  <p class="text-muted small">Aktifkan opsi "Gunakan Tahapan Wawancara" pada pengaturan Jalur Pendaftaran.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <!-- End scoped scroll wrapper -->

      @if($registrations->hasPages())
        <div class="mt-3">
          {{ $registrations->links() }}
        </div>
      @endif
    </div>
  </div>
</main>

<!-- Modal Atur Jadwal -->
<div class="modal fade" id="jadwalModal" tabindex="-1" aria-labelledby="jadwalModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('wawancara.schedule') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="jadwalModalLabel">Atur Jadwal Wawancara</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="pendaftaran_id" id="jadwal_pendaftaran_id" value="">

          <div class="mb-3">
            <p class="fw-semibold mb-0" id="jadwal_nama_label">Nama Mahasiswa</p>
            <small class="text-muted">Isi jadwal wawancara untuk calon mahasiswa ini.</small>
          </div>

          <div class="mb-3">
            <label for="tanggal_wawancara" class="form-label">Tanggal Wawancara</label>
            <input type="date" name="tanggal_wawancara" id="tanggal_wawancara" class="form-control">
          </div>

          <div class="mb-3">
            <label for="jam_wawancara" class="form-label">Jam Wawancara</label>
            <input type="time" name="jam_wawancara" id="jam_wawancara" class="form-control">
          </div>

          <div class="mb-3">
            <label for="lokasi_wawancara" class="form-label">Lokasi / Virtual Link</label>
            <input type="text" name="lokasi_wawancara" id="lokasi_wawancara" class="form-control" placeholder="Contoh: Gedung A Ruang 101 atau https://meet.google.com/xxx">
            <div class="form-text">Masukkan nama ruangan fisik atau URL link virtual meeting.</div>
          </div>

          <!-- Nama Pewawancara (text input, not relational) -->
          <div class="mb-3">
            <label for="nama_pewawancara" class="form-label">Nama Pewawancara</label>
            <input type="text" name="nama_pewawancara" id="nama_pewawancara" class="form-control" placeholder="Masukkan nama dosen / tim pewawancara...">
            <div class="form-text">Ketik nama pewawancara secara manual. Super Admin yang mengelola.</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Input Hasil -->
<div class="modal fade" id="hasilModal" tabindex="-1" aria-labelledby="hasilModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('wawancara.hasil') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="hasilModalLabel">Input Hasil Wawancara</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="pendaftaran_id" id="hasil_pendaftaran_id" value="">

          <div class="mb-3">
            <p class="fw-semibold mb-0" id="hasil_nama_label">Nama Mahasiswa</p>
            <small class="text-muted">Input hasil wawancara untuk calon mahasiswa ini.</small>
          </div>

          <div class="mb-3">
            <label for="status_wawancara" class="form-label">Status Wawancara <span class="text-danger">*</span></label>
            <select name="status_wawancara" id="status_wawancara" class="form-select" required>
              <option value="">Pilih status...</option>
              <option value="Lolos">Lolos</option>
              <option value="Tidak Lolos">Tidak Lolos</option>
            </select>
            <div class="form-text text-danger">Jika memilih "Tidak Lolos", sistem akan otomatis menggagalkan pendaftar terlepas dari nilai ujian online.</div>
          </div>

          <div class="mb-3">
            <label for="catatan_pewawancara" class="form-label">Catatan Pewawancara</label>
            <textarea name="catatan_pewawancara" id="catatan_pewawancara" rows="4" class="form-control" placeholder="Catatan hasil wawancara..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Hasil</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Jadwal Modal
  const jadwalModal = document.getElementById('jadwalModal');
  jadwalModal.addEventListener('show.bs.modal', function(event) {
    const btn = event.relatedTarget;
    document.getElementById('jadwal_pendaftaran_id').value = btn.getAttribute('data-pendaftaran-id');
    document.getElementById('jadwal_nama_label').textContent = 'Nama: ' + btn.getAttribute('data-nama');
    document.getElementById('tanggal_wawancara').value = btn.getAttribute('data-tanggal');
    document.getElementById('jam_wawancara').value = btn.getAttribute('data-jam');
    document.getElementById('lokasi_wawancara').value = btn.getAttribute('data-lokasi');
    document.getElementById('nama_pewawancara').value = btn.getAttribute('data-nama-pewawancara');
  });

  // Hasil Modal
  const hasilModal = document.getElementById('hasilModal');
  hasilModal.addEventListener('show.bs.modal', function(event) {
    const btn = event.relatedTarget;
    document.getElementById('hasil_pendaftaran_id').value = btn.getAttribute('data-pendaftaran-id');
    document.getElementById('hasil_nama_label').textContent = 'Nama: ' + btn.getAttribute('data-nama');
    document.getElementById('status_wawancara').value = btn.getAttribute('data-status');
    document.getElementById('catatan_pewawancara').value = btn.getAttribute('data-catatan');
  });
});
</script>
@endpush
@endsection