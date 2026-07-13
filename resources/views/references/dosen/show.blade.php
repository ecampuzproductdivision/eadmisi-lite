@extends('layouts.app')

@section('content')
<main class="p-2">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2 text-success"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
  @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-triangle fs-4 me-2"></i>
      {{ session('warning') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card border-1 shadow-lg mb-4">
    <div class="card-body p-4">
      <div class="row align-items-center">
        <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
          @if($dosen->foto)
            <img src="{{ asset('storage/' . $dosen->foto) }}" alt="Foto Dosen" class="rounded-circle shadow-sm" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #fff;">
          @else
            <div class="avatar-text-lg rounded-circle mx-auto mx-md-0 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 2.5rem; font-weight: bold; border: 3px solid #fff;">
              {{ substr($dosen->nama_lengkap, 0, 1) }}
            </div>
          @endif
        </div>
        <div class="col-md text-center text-md-start">
          <h2 class="fw-bold mb-1">
            {{ $dosen->gelar_depan ? $dosen->gelar_depan . ' ' : '' }}{{ $dosen->nama_lengkap }}{{ $dosen->gelar_belakang ? ', ' . $dosen->gelar_belakang : '' }}
          </h2>
          <p class="text-muted mb-2 fs-5">
            {{ $dosen->jabatan_fungsional ?? 'Dosen' }} di {{ $dosen->programStudi->prodiNamaResmi ?? 'Program Studi' }}
          </p>
          <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
            <span class="badge bg-light text-dark border"><i class="ti ti-id me-1"></i> NIDN: {{ $dosen->nidn ?? '-' }}</span>
            <span class="badge bg-light text-dark border"><i class="ti ti-mail me-1"></i> {{ $dosen->email_institusi }}</span>
            <span class="badge {{ $dosen->status_dosen === 'Aktif' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }}">
              {{ $dosen->status_dosen }}
            </span>
          </div>
        </div>
        <div class="col-md-auto mt-3 mt-md-0 text-center text-md-end">
          <a href="{{ route('dosen.edit', $dosen->id_dosen) }}" class="btn btn-dark"><i class="ti ti-edit me-2"></i>Ubah</a>
          <a href="{{ route('dosen.index') }}" class="btn btn-light border fw-semibold text-dark ms-2"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
        </div>
      </div>
    </div>
  </div>
<div class="row mb-8">
  <div class="col-12">
      <ul class="nav nav-lb-tab border-bottom" id="dosenTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active fw-semibold" id="profil-tab" data-bs-toggle="tab" data-bs-target="#profil" type="button" role="tab"><i class="ti ti-user me-2"></i>Profil & Kepegawaian</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-semibold" id="pendidikan-tab" data-bs-toggle="tab" data-bs-target="#pendidikan" type="button" role="tab"><i class="ti ti-book me-2"></i>Riwayat Pendidikan</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-semibold" id="jabatan-tab" data-bs-toggle="tab" data-bs-target="#jabatan" type="button" role="tab"><i class="ti ti-medal me-2"></i>Jabatan Fungsional</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-semibold" id="kompetensi-tab" data-bs-toggle="tab" data-bs-target="#kompetensi" type="button" role="tab"><i class="ti ti-certificate me-2"></i>Keahlian & Serdos</button>
        </li>
      </ul>
  </div>
</div>
<div class="row mb-8">
  <div class="col-12">
      
          <div class="tab-content" id="dosenTabsContent">
            <!-- Tab: Profil -->
            <div class="tab-pane fade show active" id="profil" role="tabpanel">
              <div class="row g-4">
                <div class="col-md-6">
                  <div class="card card-lg h-100">
                    <div class="card-body p-4">
                      <h5 class="fw-bold mb-4"><i class="ti ti-address-book me-2"></i>Informasi Pribadi</h5>
                      <table class="table table-borderless table-sm mb-0">
                        <tr><td width="40%" class="text-muted">NIK</td><td class="fw-medium">{{ $dosen->nik }}</td></tr>
                        <tr><td class="text-muted">NIDK / NUPN</td><td class="fw-medium">{{ $dosen->nidk ?? '-' }} / {{ $dosen->nupn ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Tempat, Tgl Lahir</td><td class="fw-medium">{{ $dosen->tempat_lahir }}, {{ \Carbon\Carbon::parse($dosen->tanggal_lahir)->format('d M Y') }}</td></tr>
                        <tr><td class="text-muted">Jenis Kelamin</td><td class="fw-medium">{{ $dosen->jenis_kelamin }}</td></tr>
                        <tr><td class="text-muted">Agama</td><td class="fw-medium">{{ $dosen->agama ?? '-' }}</td></tr>
                        <tr><td class="text-muted">No. Handphone</td><td class="fw-medium">{{ $dosen->no_hp }}</td></tr>
                        <tr><td class="text-muted">Alamat Domisili</td><td class="fw-medium">{{ $dosen->alamat_domisili ?? '-' }}</td></tr>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card card-lg h-100">
                    <div class="card-body p-4">
                      <h5 class="fw-bold mb-4"><i class="ti ti-briefcase me-2"></i>Status Akademik & Kepegawaian</h5>
                      <table class="table table-borderless table-sm mb-0">
                        <tr><td width="40%" class="text-muted">Jenis Dosen</td><td class="fw-medium">{{ $dosen->jenis_dosen }}</td></tr>
                        <tr><td class="text-muted">Status Kepegawaian</td><td class="fw-medium">{{ $dosen->status_kepegawaian }}</td></tr>
                        <tr><td class="text-muted">Mulai Bertugas</td><td class="fw-medium">{{ \Carbon\Carbon::parse($dosen->tanggal_mulai_bertugas)->format('d M Y') }}</td></tr>
                        <tr><td class="text-muted">Jenjang Terakhir</td><td class="fw-medium">{{ $dosen->jenjang_pendidikan_terakhir }} {{ $dosen->bidang_studi_terakhir }}</td></tr>
                        <tr><td class="text-muted">Perguruan Tinggi Asal</td><td class="fw-medium">{{ $dosen->perguruan_tinggi_asal }}</td></tr>
                        <tr><td class="text-muted">Sertifikasi Dosen</td><td class="fw-medium">
                          @if($dosen->is_sertifikasi_dosen)
                            <span class="badge bg-success-subtle text-success">Ya ({{ $dosen->tahun_sertifikasi }})</span>
                          @else
                            <span class="badge bg-secondary-subtle text-secondary">Belum</span>
                          @endif
                        </td></tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tab: Pendidikan -->
            <div class="tab-pane fade" id="pendidikan" role="tabpanel">
              <div class="card card-lg">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4">
                  <h5 class="fw-bold mb-0">Riwayat Pendidikan Tinggi</h5>
                  <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#modalAddPendidikan">
                    <i class="ti ti-plus me-1"></i>Tambah
                  </button>
                </div>
                <div class="table-responsive">
                  <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>Jenjang</th>
                        <th>Perguruan Tinggi</th>
                        <th>Bidang Studi</th>
                        <th>Tahun Masuk-Lulus</th>
                        <th>Status</th>
                        <th width="80">Aksi</th>
                      </tr>
                    </thead>
                    <tbody id="tbody-pendidikan">
                      @forelse($dosen->pendidikans as $pend)
                        <tr id="row-pendidikan-{{ $pend->id_pendidikan }}">
                          <td class="fw-semibold">{{ $pend->jenjang }}</td>
                          <td>{{ $pend->nama_pt }} <div class="small text-muted">{{ $pend->negara_pt }}</div></td>
                          <td>{{ $pend->bidang_studi }}</td>
                          <td>{{ $pend->tahun_masuk }} - {{ $pend->tahun_lulus ?? 'Sekarang' }}</td>
                          <td>
                            @if($pend->is_pendidikan_terakhir)
                              <span class="badge bg-primary-subtle text-primary">Terakhir</span>
                            @endif
                          </td>
                          <td>
                            <button type="button" class="btn btn-sm btn-light text-danger btn-delete-sub" data-url="{{ route('dosen.pendidikan.destroy', $pend->id_pendidikan) }}" data-target="#row-pendidikan-{{ $pend->id_pendidikan }}">
                              <i class="ti ti-trash"></i>
                            </button>
                          </td>
                        </tr>
                      @empty
                        <tr id="empty-pendidikan"><td colspan="6" class="text-center text-muted py-4">Belum ada data pendidikan</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Tab: Jabatan -->
            <div class="tab-pane fade" id="jabatan" role="tabpanel">
              <div class="card card-lg">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4">
                  <h5 class="fw-bold mb-0">Riwayat Jabatan Fungsional</h5>
                  <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#modalAddJabatan">
                    <i class="ti ti-plus me-1"></i>Tambah
                  </button>
                </div>
                <div class="table-responsive">
                  <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>Jabatan</th>
                        <th>TMT</th>
                        <th>Angka Kredit</th>
                        <th>No SK & Penerbit</th>
                        <th>Status</th>
                        <th width="80">Aksi</th>
                      </tr>
                    </thead>
                    <tbody id="tbody-jabatan">
                      @forelse($dosen->jabatans as $jab)
                        <tr id="row-jabatan-{{ $jab->id_jabatan }}">
                          <td class="fw-semibold">{{ $jab->jabatan_fungsional }}</td>
                          <td>{{ \Carbon\Carbon::parse($jab->tmt)->format('d M Y') }}</td>
                          <td>{{ $jab->angka_kredit ?? '-' }}</td>
                          <td>{{ $jab->no_sk_jabatan }}<div class="small text-muted">{{ $jab->instansi_penerbit_sk }}</div></td>
                          <td>
                            @if($jab->is_jabatan_aktif)
                              <span class="badge bg-success-subtle text-success">Aktif</span>
                            @endif
                          </td>
                          <td>
                            <button type="button" class="btn btn-sm btn-light text-danger btn-delete-sub" data-url="{{ route('dosen.jabatan.destroy', $jab->id_jabatan) }}" data-target="#row-jabatan-{{ $jab->id_jabatan }}">
                              <i class="ti ti-trash"></i>
                            </button>
                          </td>
                        </tr>
                      @empty
                        <tr id="empty-jabatan"><td colspan="6" class="text-center text-muted py-4">Belum ada data jabatan</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Tab: Kompetensi & Serdos -->
            <div class="tab-pane fade" id="kompetensi" role="tabpanel">
              <div class="row g-4">
                <div class="col-md-6">
                  <div class="card border-1 card-lg">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4">
                      <h5 class="fw-bold mb-0">Keahlian</h5>
                      <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#modalAddKeahlian">
                        <i class="ti ti-plus me-1"></i> Tambah
                      </button>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                          <tr>
                            <th>Keahlian</th>
                            <th>Kategori</th>
                            <th>Level</th>
                            <th width="50">Aksi</th>
                          </tr>
                        </thead>
                        <tbody id="tbody-keahlian">
                          @forelse($dosen->keahlians as $k)
                            <tr id="row-keahlian-{{ $k->id_keahlian }}">
                              <td class="fw-semibold">{{ $k->nama_keahlian }}</td>
                              <td>{{ $k->kategori_keahlian }}</td>
                              <td>{{ $k->level_keahlian }}</td>
                              <td>
                                <button type="button" class="btn btn-sm btn-light text-danger btn-delete-sub" data-url="{{ route('dosen.keahlian.destroy', $k->id_keahlian) }}" data-target="#row-keahlian-{{ $k->id_keahlian }}">
                                  <i class="ti ti-trash"></i>
                                </button>
                              </td>
                            </tr>
                          @empty
                            <tr id="empty-keahlian"><td colspan="4" class="text-center text-muted py-4">Belum ada keahlian</td></tr>
                          @endforelse
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="card border-1 card-lg">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4">
                      <h5 class="fw-bold mb-0">Sertifikasi Profesional</h5>
                      <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#modalAddSertifikasi">
                        <i class="ti ti-plus me-1"></i> Tambah
                      </button>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                          <tr>
                            <th>Sertifikasi</th>
                            <th>Penerbit</th>
                            <th>Tgl Berlaku</th>
                            <th width="50">Aksi</th>
                          </tr>
                        </thead>
                        <tbody id="tbody-sertifikasi">
                          @forelse($dosen->sertifikasis as $s)
                            <tr id="row-sertifikasi-{{ $s->id_sertifikasi }}">
                              <td>
                                <div class="fw-semibold">{{ $s->nama_sertifikasi }}</div>
                                <div class="small text-muted">{{ $s->no_sertifikat }}</div>
                              </td>
                              <td>{{ $s->lembaga_penerbit }}</td>
                              <td>{{ \Carbon\Carbon::parse($s->tanggal_terbit)->format('M Y') }} - {{ $s->tanggal_berlaku_akhir ? \Carbon\Carbon::parse($s->tanggal_berlaku_akhir)->format('M Y') : 'Selamanya' }}</td>
                              <td>
                                <button type="button" class="btn btn-sm btn-light text-danger btn-delete-sub" data-url="{{ route('dosen.sertifikasi.destroy', $s->id_sertifikasi) }}" data-target="#row-sertifikasi-{{ $s->id_sertifikasi }}">
                                  <i class="ti ti-trash"></i>
                                </button>
                              </td>
                            </tr>
                          @empty
                            <tr id="empty-sertifikasi"><td colspan="4" class="text-center text-muted py-4">Belum ada sertifikasi</td></tr>
                          @endforelse
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
      </div>
    </div>
</main>

<!-- Example Modal for Pendidikan (Simulated for brevity) -->
<div class="modal fade" id="modalAddPendidikan" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content form-ajax" action="{{ route('dosen.pendidikan.store', $dosen->id_dosen) }}" method="POST">
      @csrf
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold">Tambah Pendidikan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Jenjang <span class="text-danger">*</span></label>
          <select name="jenjang" class="form-select" required>
            @foreach(['S1', 'S2', 'S3', 'Profesi', 'Sp1', 'Sp2'] as $j) <option value="{{ $j }}">{{ $j }}</option> @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Nama PT <span class="text-danger">*</span></label>
          <input type="text" name="nama_pt" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Bidang Studi <span class="text-danger">*</span></label>
          <input type="text" name="bidang_studi" class="form-control" required>
        </div>
        <div class="row mb-3">
          <div class="col-6"><label class="form-label">Negara</label><input type="text" name="negara_pt" class="form-control" value="Indonesia" required></div>
          <div class="col-6"><label class="form-label">Tahun Masuk</label><input type="number" name="tahun_masuk" class="form-control" required></div>
        </div>
        <div class="form-check form-switch mt-2">
          <input class="form-check-input" type="checkbox" name="is_pendidikan_terakhir" value="1" checked>
          <label class="form-check-label">Jadikan Pendidikan Terakhir</label>
        </div>
      </div>
      <div class="modal-footer border-top-0 pt-0">
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<style>
.custom-tabs .nav-link {
  color: #64748b;
  border: none;
  border-bottom: 2px solid transparent;
  padding: 1rem 1.5rem;
}
.custom-tabs .nav-link:hover {
  color: #3b82f6;
}
.custom-tabs .nav-link.active {
  color: #2563eb;
  border-bottom: 2px solid #2563eb;
  background: transparent;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Generic AJAX Submitter
  document.querySelectorAll('.form-ajax').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const btn = form.querySelector('button[type="submit"]');
      const originalText = btn.innerHTML;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
      btn.disabled = true;

      fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: { 'Accept': 'application/json' }
      })
      .then(r => r.json())
      .then(res => {
        if(res.success) {
          location.reload(); // Quick refresh to show data (in real app we might prepend row)
        } else {
          alert(res.message || 'Gagal menyimpan.');
        }
      })
      .catch(err => alert('Terjadi kesalahan.'))
      .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
      });
    });
  });

  // Generic AJAX Deleter
  document.querySelectorAll('.btn-delete-sub').forEach(btn => {
    btn.addEventListener('click', function() {
      if (!(await confirmAsync('Yakin ingin menghapus data ini?')) return;
      
      const url = this.getAttribute('data-url');
      const targetId = this.getAttribute('data-target');
      
      fetch(url, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
      })
      .then(r => r.json())
      .then(res => {
        if(res.success) {
          document.querySelector(targetId).remove();
        } else {
          alert(res.message || 'Gagal menghapus.');
        }
      })
      .catch(err => alert('Terjadi kesalahan.'));
    });
  });
});
</script>
@endsection
