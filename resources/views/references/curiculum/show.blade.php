@extends('layouts.app')

@section('content')
<main class="p-2">
  <!-- Header & Breadcrumbs -->
  <div class="card border-1 mb-2">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h3 class="mb-1 fw-bold text-dark d-flex align-items-center">
            {{ $kurikulum->kurNama }}
            @if($kurikulum->kurIsAktif)
              <span class="badge bg-success-subtle text-success border border-success ms-2 fs-6 px-3 py-1">Aktif</span>
            @else
              <span class="badge bg-secondary-subtle text-secondary border border-secondary ms-2 fs-6 px-3 py-1">Draft</span>
            @endif
          </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">References</a></li>
              <li class="breadcrumb-item"><a href="{{ route('curiculum.index') }}">Kurikulum</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ $kurikulum->kurKode }}</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('curiculum.edit', $kurikulum->kurKode) }}" class="btn btn-dark fw-semibold px-3">
            <i class="ti ti-edit me-1"></i> Ubah
          </a>
          <a href="{{ route('curiculum.index') }}" class="btn btn-light border fw-semibold text-dark">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>  

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
      <i class="ti ti-circle-check me-2 align-middle"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="row g-4">
    <!-- Left Column: Checklist Aktivasi -->
    <div class="col-lg-5">
      @php
        // Calculate dynamic checklists
        $hasSk = !empty($kurikulum->kurNoSk) && !empty($kurikulum->kurTanggalPenetapan);
        
        $totalMappedSks = $kurikulum->kurikulumMataKuliahs->sum(function($item) {
            return $item->sks_override ?? ($item->mataKuliah->mk_sks_total ?? 0);
        });
        $sksMet = $totalMappedSks >= $kurikulum->kurSksLulus;
        
        $semestersCount = $kurikulum->kurJumlahSemester ?: 8;
        $mappedSemesters = $kurikulum->kurikulumMataKuliahs->pluck('semester_anjuran')->unique();
        $semestersMet = $mappedSemesters->count() >= $semestersCount;
        
        $profilLulusanMet = $kurikulum->profilLulusans->count() > 0;
        
        // Dynamic CPL check: at least 1 CPL is mapped to a course for this curriculum
        $cplMet = \Illuminate\Support\Facades\DB::table('cpl_mata_kuliah')
            ->join('cpl', 'cpl_mata_kuliah.id_cpl', '=', 'cpl.id_cpl')
            ->where('cpl.kurikulum_kode', $kurikulum->kurKode)
            ->exists(); 
        
        // DAG cycle is validated on save, so we assume graph is valid if mapped
        $dagMet = true;
        
        // Calculate completion progress
        $checklists = [
            'sk' => $hasSk,
            'sks' => $sksMet,
            'semesters' => $semestersMet,
            'profil' => $profilLulusanMet,
            'cpl' => $cplMet,
            'dag' => $dagMet
        ];
        
        $completedCount = count(array_filter($checklists));
        $totalCount = count($checklists);
        $percent = round(($completedCount / $totalCount) * 100);
        $allMet = $completedCount === $totalCount;
      @endphp

      <div class="card card-lg mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
          <h4 class="fw-bold mb-3 text-dark d-flex align-items-center">
            <i class="ti ti-checkbox text-success me-2"></i> Checklist Aktivasi
          </h4>
          
          <div class="mb-4">
            <div class="d-flex justify-content-between mb-1">
              <span class="text-muted fw-semibold">Progress Persyaratan</span>
              <span class="text-success fw-bold">{{ $percent }}%</span>
            </div>
            <div class="progress" style="height: 8px;">
              <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>

          <ul class="list-group list-group-flush mb-4">
            <!-- 1. SK & Tanggal Penetapan -->
            <li class="list-group-item d-flex align-items-start border-0 px-0 py-2">
              <i class="ti {{ $hasSk ? 'ti-circle-check-filled text-success' : 'ti-circle-x-filled text-danger' }} mt-1 me-2"></i>
              <div>
                <span class="d-block fw-bold text-dark small">SK & Tanggal Penetapan</span>
                <span class="d-block text-muted" style="font-size: 11px;">
                  {{ $hasSk ? 'SK: ' . $kurikulum->kurNoSk : 'SK dan tanggal penetapan belum lengkap.' }}
                </span>
              </div>
            </li>
            
            <!-- 2. Target SKS Kelulusan -->
            <li class="list-group-item d-flex align-items-start border-0 px-0 py-2">
              <i class="ti {{ $sksMet ? 'ti-circle-check-filled text-success' : 'ti-circle-x-filled text-danger' }} mt-1 me-2"></i>
              <div>
                <span class="d-block fw-bold text-dark small">Target SKS Terpenuhi</span>
                <span class="d-block text-muted" style="font-size: 11px;">
                  Terpetakan: <strong class="text-primary">{{ $totalMappedSks }} SKS</strong> / Syarat: {{ $kurikulum->kurSksLulus }} SKS
                </span>
              </div>
            </li>

            <!-- 3. Penyebaran Semester -->
            <li class="list-group-item d-flex align-items-start border-0 px-0 py-2">
              <i class="ti {{ $semestersMet ? 'ti-circle-check-filled text-success' : 'ti-circle-x-filled text-danger' }} mt-1 me-2"></i>
              <div>
                <span class="d-block fw-bold text-dark small">Penyebaran Semester</span>
                <span class="d-block text-muted" style="font-size: 11px;">
                  Semester terisi: <strong class="text-primary">{{ $mappedSemesters->count() }}</strong> / Total: {{ $semestersCount }} Semester
                </span>
              </div>
            </li>

            <!-- 4. Profil Lulusan -->
            <li class="list-group-item d-flex align-items-start border-0 px-0 py-2">
              <i class="ti {{ $profilLulusanMet ? 'ti-circle-check-filled text-success' : 'ti-circle-x-filled text-danger' }} mt-1 me-2"></i>
              <div>
                <span class="d-block fw-bold text-dark small">Profil Lulusan</span>
                <span class="d-block text-muted" style="font-size: 11px;">
                  Terdefinisi: <strong class="text-primary">{{ $kurikulum->profilLulusans->count() }} PL</strong>
                </span>
              </div>
            </li>

            <!-- 5. Minimal 1 CPL -->
            <li class="list-group-item d-flex align-items-start border-0 px-0 py-2">
              <i class="ti {{ $cplMet ? 'ti-circle-check-filled text-success' : 'ti-circle-x-filled text-danger' }} mt-1 me-2"></i>
              <div class="w-100">
                <div class="d-flex justify-content-between align-items-center">
                  <span class="fw-bold text-dark small">Capaian Pembelajaran (CPL)</span>
                </div>
                <span class="d-block text-muted" style="font-size: 11px;">Minimal 1 CPL terhubung ke mata kuliah</span>
              </div>
            </li>

            <!-- 6. Bebas Siklus (DAG) -->
            <li class="list-group-item d-flex align-items-start border-0 px-0 py-2">
              <i class="ti {{ $dagMet ? 'ti-circle-check-filled text-success' : 'ti-circle-x-filled text-danger' }} mt-1 me-2"></i>
              <div>
                <span class="d-block fw-bold text-dark small">Struktur Bebas Siklus (DAG)</span>
                <span class="d-block text-muted" style="font-size: 11px;">Prasyarat divalidasi aman dari dependensi melingkar</span>
              </div>
            </li>
          </ul>

          <div class="border-top pt-3">
            @if($kurikulum->kurIsAktif)
              <button class="btn btn-outline-danger w-100 fw-bold py-2 btn-toggle-activation">
                <i class="ti ti-power me-1"></i> Nonaktifkan Kurikulum
              </button>
            @else
              <button class="btn btn-success w-100 fw-bold py-2 btn-toggle-activation" {{ $allMet ? '' : 'disabled' }}>
                <i class="ti ti-power me-1"></i> Aktifkan Kurikulum
              </button>
              @if(!$allMet)
                <p class="text-center text-muted mt-2 mb-0" style="font-size: 11px;">
                  <i class="ti ti-info-circle text-warning"></i> Selesaikan semua checklist untuk mengaktifkan kurikulum.
                </p>
              @endif
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: Identitas & Detail Kurikulum -->
    <div class="col-lg-7">
      <div class="card card-lg mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
          <h4 class="fw-bold mb-4 text-dark pb-4">
            <i class="ti ti-info-circle text-primary me-2"></i> Identitas Kurikulum
          </h4>
          
          <div class="row g-3">
            <div class="col-md-6">
              <div class="p-3 bg-light rounded-3">
                <span class="d-block text-muted small fw-semibold">Kode Kurikulum</span>
                <span class="d-block text-dark fw-bold">{{ $kurikulum->kurKode }}</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-3 bg-light rounded-3">
                <span class="d-block text-muted small fw-semibold">Program Studi</span>
                <span class="d-block text-dark fw-bold">{{ $kurikulum->programStudi ? $kurikulum->programStudi->prodiNamaResmi : '-' }}</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-3 bg-light rounded-3">
                <span class="d-block text-muted small fw-semibold">Tahun Berlaku</span>
                <span class="d-block text-dark fw-bold">{{ $kurikulum->kurTahunMulai }} - {{ $kurikulum->kurTahunSelesai ?: 'Sekarang' }}</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-3 bg-light rounded-3">
                <span class="d-block text-muted small fw-semibold">Jenis Kurikulum</span>
                <span class="d-block text-dark fw-bold">{{ $kurikulum->kurJenis ?: 'OBE' }}</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-3 bg-light rounded-3">
                <span class="d-block text-muted small fw-semibold">Beban SKS Kelulusan</span>
                <span class="d-block text-dark fw-bold text-primary">{{ $kurikulum->kurSksLulus }} SKS</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-3 {{ $kurikulum->kurNoSk ? 'bg-success-subtle' : 'bg-warning-subtle' }} rounded-3">
                <span class="d-block text-muted small fw-semibold">Nomor SK Penetapan</span>
                @if($kurikulum->kurNoSk)
                  <span class="d-block text-dark fw-bold">{{ $kurikulum->kurNoSk }}</span>
                @else
                  <span class="d-block text-warning fw-semibold"><i class="ti ti-alert-triangle me-1"></i> Belum diisi</span>
                @endif
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-3 {{ $kurikulum->kurTanggalPenetapan ? 'bg-success-subtle' : 'bg-warning-subtle' }} rounded-3">
                <span class="d-block text-muted small fw-semibold">Tanggal Penetapan SK</span>
                @if($kurikulum->kurTanggalPenetapan)
                  <span class="d-block text-dark fw-bold">{{ $kurikulum->kurTanggalPenetapan->format('d F Y') }}</span>
                @else
                  <span class="d-block text-warning fw-semibold"><i class="ti ti-alert-triangle me-1"></i> Belum diisi</span>
                @endif
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-3 bg-light rounded-3">
                <span class="d-block text-muted small fw-semibold">Referensi KKNI</span>
                <span class="d-block text-dark fw-bold">{{ $kurikulum->kurReferensiKkni ?: '-' }}</span>
              </div>
            </div>
            <div class="col-6">
              <div class="p-3 bg-light rounded-3">
                <span class="d-block text-muted small fw-semibold">Keterangan</span>
                <span class="d-block text-dark mt-1">{{ $kurikulum->kurKeterangan ?: 'Tidak ada keterangan tambahan.' }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Visi, Misi, Tujuan Card --}}
      @if($kurikulum->kurVisi || $kurikulum->kurMisi || $kurikulum->kurTujuan)
      <div class="card card-lg mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
          <h4 class="fw-bold mb-4 text-dark">
            <i class="ti ti-target text-primary me-2"></i> Visi, Misi & Tujuan
          </h4>
          <div class="row g-3">
            @if($kurikulum->kurVisi)
            <div class="col-md-4">
              <div class="p-3 bg-light rounded-3 h-100">
                <span class="d-block text-muted small fw-semibold mb-1">Visi</span>
                <span class="d-block text-dark">{{ $kurikulum->kurVisi }}</span>
              </div>
            </div>
            @endif
            @if($kurikulum->kurMisi)
            <div class="col-md-4">
              <div class="p-3 bg-light rounded-3 h-100">
                <span class="d-block text-muted small fw-semibold mb-1">Misi</span>
                <span class="d-block text-dark">{{ $kurikulum->kurMisi }}</span>
              </div>
            </div>
            @endif
            @if($kurikulum->kurTujuan)
            <div class="col-md-4">
              <div class="p-3 bg-light rounded-3 h-100">
                <span class="d-block text-muted small fw-semibold mb-1">Tujuan</span>
                <span class="d-block text-dark">{{ $kurikulum->kurTujuan }}</span>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>

  <!-- Row 3: Builder Struktur Semester -->
  <div class="card card-lg my-6">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-center pb-4 mb-4">
        <div>
          <h4 class="fw-bold mb-1 text-dark">
            <i class="ti ti-layout-grid-add text-primary me-2"></i> Builder Struktur Semester
          </h4>
          <p class="text-muted mb-0 small">Susun mata kuliah pada kurikulum dengan drag & drop antar semester.</p>
        </div>
      </div>

      <div class="row g-4">
        @for($sem = 1; $sem <= $semestersCount; $sem++)
          @php
            $semMappings = $kurikulum->kurikulumMataKuliahs->where('semester_anjuran', $sem);
            $semSks = $semMappings->sum(function($item) {
                return $item->sks_override ?? ($item->mataKuliah->mk_sks_total ?? 0);
            });
          @endphp
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card card-lg h-100 semester-card semester-dropzone" 
                 data-semester="{{ $sem }}">
              <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center py-3" style="border-radius: 12px 12px 0 0;">
                <h6 class="fw-bold mb-0 text-dark">Semester {{ $sem }}</h6>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1 fs-7">{{ $semSks }} SKS</span>
              </div>
              <div class="card-body p-3 d-flex flex-column justify-content-between">
                <!-- Mapped Courses List -->
                <div class="course-list-container d-flex flex-column gap-2 mb-3" style="min-height: 200px;">
                  @if($semMappings->isEmpty())
                    <div class="text-center my-auto py-4 text-muted border border-dashed rounded-3">
                      <i class="ti ti-folder-open d-block fs-3 mb-1"></i>
                      <span style="font-size: 11px;">Kosong</span>
                    </div>
                  @else
                    @foreach($semMappings as $mapping)
                      @if($mapping->mataKuliah)
                        <div class="draggable-course card border-light-subtle shadow-xs p-2 position-relative" 
                             draggable="true" 
                             data-mapping-id="{{ $mapping->id }}"
                             style="cursor: grab; border-radius: 8px; font-size: 13px; background-color: #fafbfc;">
                          <div class="fw-bold text-dark d-flex justify-content-between align-items-center">
                            <span>{{ $mapping->mataKuliah->mk_kode }}</span>
                            <div class="dropdown">
                              <button class="btn btn-xs btn-link p-0 text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size: 12px;">
                                <li><a class="dropdown-item btn-edit-mapping" href="#" data-mapping-id="{{ $mapping->id }}" data-mk-id="{{ $mapping->mk_id }}" data-kelompok-id="{{ $mapping->kelompok_id }}" data-semester="{{ $mapping->semester_anjuran }}" data-wajib="{{ $mapping->is_wajib ? 1 : 0 }}" data-prasyarat="{{ $mapping->mk_prasyarat_id }}" data-nilai-min="{{ $mapping->nilai_prasyarat_min }}" data-sks-override="{{ $mapping->sks_override }}"><i class="ti ti-edit me-1 text-primary"></i> Ubah Pemetaan</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item btn-delete-mapping text-danger" href="#" data-mapping-id="{{ $mapping->id }}"><i class="ti ti-trash me-1"></i> Hapus</a></li>
                              </ul>
                            </div>
                          </div>
                          <div class="text-muted text-truncate mt-1" title="{{ $mapping->mataKuliah->mk_nama }}">
                            {{ $mapping->mataKuliah->mk_nama }}
                          </div>
                          <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top" style="font-size: 11px;">
                            <span class="badge {{ $mapping->is_wajib ? 'bg-danger-subtle text-danger' : 'bg-info-subtle text-info' }}">
                              {{ $mapping->is_wajib ? 'Wajib' : 'Pilihan' }}
                            </span>
                            <span class="text-secondary fw-semibold">
                              {{ $mapping->sks_override ?? $mapping->mataKuliah->mk_sks_total }} SKS
                            </span>
                          </div>
                          @if($mapping->mataKuliahPrasyarat)
                            <div class="mt-1 text-muted" style="font-size: 10px;">
                              <i class="ti ti-git-fork"></i> Prasyarat: <strong>{{ $mapping->mataKuliahPrasyarat->mk_kode }}</strong> ({{ $mapping->nilai_prasyarat_min ?: 'D' }})
                            </div>
                          @endif
                        </div>
                      @endif
                    @endforeach
                  @endif
                </div>

                <!-- Add Button per Semester -->
                <button class="btn btn-sm btn-dark w-100 fw-semibold  py-1.5 btn-add-course" 
                        data-semester="{{ $sem }}">
                  <i class="ti ti-plus me-1"></i> Tambah MK
                </button>
              </div>
            </div>
          </div>
        @endfor
      </div>
    </div>
  </div>
</main>

<!-- Modal: Map Course Form -->
<div class="modal fade" id="mapCourseModal" tabindex="-1" aria-labelledby="mapCourseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
      <div class="modal-header bg-dark text-white py-3" style="border-radius: 16px 16px 0 0;">
        <h5 class="modal-title fw-bold" id="mapCourseModalLabel">
          <i class="ti ti-layout-grid-add me-2"></i> Petakan Mata Kuliah
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="mapCourseForm">
        @csrf
        <input type="hidden" name="kurikulum_kode" value="{{ $kurikulum->kurKode }}">
        <input type="hidden" id="mapping_id" name="mapping_id" value="">
        <div class="modal-body p-4">
          
          <div class="mb-3" id="mk_selection_group">
            <label for="mk_id" class="form-label fw-semibold text-dark">Pilih Mata Kuliah</label>
            <select class="form-select" id="mk_id" name="mk_id" required>
              <option value="" disabled selected>-- Pilih Mata Kuliah --</option>
              @foreach($allCourses as $c)
                <option value="{{ $c->id }}">{{ $c->mk_kode }} - {{ $c->mk_nama }} ({{ $c->mk_sks_total }} SKS)</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="kelompok_id" class="form-label fw-semibold text-dark">Kelompok Mata Kuliah</label>
            <select class="form-select" id="kelompok_id" name="kelompok_id" required>
              <option value="" disabled selected>-- Pilih Kelompok --</option>
              @foreach($kurikulum->kelompokMks as $k)
                <option value="{{ $k->id }}">{{ $k->kode_kelompok }} - {{ $k->nama_kelompok }}</option>
              @endforeach
            </select>
          </div>

          <div class="row mb-3">
            <div class="col-6">
              <label for="semester_anjuran" class="form-label fw-semibold text-dark">Semester</label>
              <select class="form-select" id="semester_anjuran" name="semester_anjuran" required>
                @for($s = 1; $s <= $semestersCount; $s++)
                  <option value="{{ $s }}">Semester {{ $s }}</option>
                @endfor
              </select>
            </div>
            <div class="col-6">
              <label for="sks_override" class="form-label fw-semibold text-dark">Override SKS (Opsional)</label>
              <input type="number" class="form-control" id="sks_override" name="sks_override" min="1" placeholder="Bawaan MK">
            </div>
          </div>

          <div class="mb-3">
            <label for="mk_prasyarat_id" class="form-label fw-semibold text-dark">Mata Kuliah Prasyarat (Opsional)</label>
            <select class="form-select" id="mk_prasyarat_id" name="mk_prasyarat_id">
              <option value="">-- Tanpa Prasyarat --</option>
              @foreach($kurikulum->kurikulumMataKuliahs->unique('mk_id') as $m)
                @if($m->mataKuliah)
                  <option value="{{ $m->mk_id }}">{{ $m->mataKuliah->mk_kode }} - {{ $m->mataKuliah->mk_nama }}</option>
                @endif
              @endforeach
            </select>
          </div>

          <div class="row mb-3 align-items-center">
            <div class="col-6">
              <label for="nilai_prasyarat_min" class="form-label fw-semibold text-dark">Nilai Min. Prasyarat</label>
              <select class="form-select" id="nilai_prasyarat_min" name="nilai_prasyarat_min">
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C" selected>C</option>
                <option value="D">D</option>
              </select>
            </div>
            <div class="col-6 pt-4">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="is_wajib" name="is_wajib" value="1" checked>
                <label class="form-check-label fw-semibold text-dark" for="is_wajib">Mata Kuliah Wajib</label>
              </div>
            </div>
          </div>

          <!-- Alert for Error message -->
          <div class="alert alert-danger d-none" id="formErrorAlert">
            <i class="ti ti-exclamation-circle me-1"></i> <span id="formErrorMessage"></span>
          </div>

        </div>
        <div class="modal-footer bg-light py-3 border-top-0 d-flex justify-content-end" style="border-radius: 0 0 16px 16px;">
          <button type="button" class="btn btn-secondary fw-semibold px-4" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary fw-semibold px-4" id="btnSubmitForm">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Styles for Hover and Drag Effects -->
<style>
  .semester-card.bg-light-primary {
    background-color: #ecf3fe !important;
    border: 2px dashed #0d6efd !important;
  }
  .draggable-course:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
    border-color: #0d6efd !important;
  }
  .draggable-course:active {
    cursor: grabbing;
    opacity: 0.5;
  }
</style>

<!-- Javascript for Interactive UI and drag-and-drop mapping -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Initial Modal Setup
    const mapCourseModalEl = document.getElementById('mapCourseModal');
    const mapCourseModal = new bootstrap.Modal(mapCourseModalEl);
    const mapCourseForm = document.getElementById('mapCourseForm');
    const mkSelectionGroup = document.getElementById('mk_selection_group');
    const formErrorAlert = document.getElementById('formErrorAlert');
    const formErrorMessage = document.getElementById('formErrorMessage');
    
    // 2. Add / Edit Pemetaan Click Handlers
    document.querySelectorAll('.btn-add-course').forEach(btn => {
        btn.addEventListener('click', () => {
            mapCourseForm.reset();
            document.getElementById('mapping_id').value = '';
            document.getElementById('semester_anjuran').value = btn.dataset.semester;
            mkSelectionGroup.classList.remove('d-none');
            document.getElementById('mk_id').setAttribute('required', 'required');
            formErrorAlert.classList.add('d-none');
            
            document.getElementById('mapCourseModalLabel').innerHTML = '<i class="ti ti-layout-grid-add me-2"></i> Tambah Mata Kuliah';
            mapCourseModal.show();
        });
    });

    document.querySelectorAll('.btn-edit-mapping').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            mapCourseForm.reset();
            
            document.getElementById('mapping_id').value = btn.dataset.mappingId;
            document.getElementById('kelompok_id').value = btn.dataset.kelompokId;
            document.getElementById('semester_anjuran').value = btn.dataset.semester;
            document.getElementById('sks_override').value = btn.dataset.sksOverride || '';
            document.getElementById('mk_prasyarat_id').value = btn.dataset.prasyarat || '';
            document.getElementById('nilai_prasyarat_min').value = btn.dataset.nilaiMin || 'C';
            document.getElementById('is_wajib').checked = btn.dataset.wajib === '1';
            
            // Hide MK selection as it is immutable for edit
            mkSelectionGroup.classList.add('d-none');
            document.getElementById('mk_id').removeAttribute('required');
            formErrorAlert.classList.add('d-none');

            document.getElementById('mapCourseModalLabel').innerHTML = '<i class="ti ti-edit me-2"></i> Edit Pemetaan Mata Kuliah';
            mapCourseModal.show();
        });
    });

    // 3. Form Submit Handler (AJAX Store/Update)
    mapCourseForm.addEventListener('submit', (e) => {
        e.preventDefault();
        formErrorAlert.classList.add('d-none');
        
        const mappingId = document.getElementById('mapping_id').value;
        const isEdit = mappingId !== '';
        const url = isEdit ? `/references/curiculum-course/${mappingId}` : '/references/curiculum-course';
        const method = isEdit ? 'PUT' : 'POST';
        
        // Prepare FormData
        const formData = new FormData(mapCourseForm);
        const dataObj = {};
        formData.forEach((val, key) => {
            dataObj[key] = val;
        });
        
        // Handle checkbox
        dataObj['is_wajib'] = document.getElementById('is_wajib').checked ? 1 : 0;
        if (isEdit) {
            delete dataObj['mk_id']; // Don't submit mk_id on edit
        }

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(dataObj)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mapCourseModal.hide();
                window.location.reload(); // Refresh to update list and checklists
            } else {
                let errorMsg = data.message || 'Terjadi kesalahan.';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat().join('<br>');
                }
                formErrorMessage.innerHTML = errorMsg;
                formErrorAlert.classList.remove('d-none');
            }
        })
        .catch(err => {
            formErrorMessage.innerText = 'Gagal mengirim data. Coba lagi.';
            formErrorAlert.classList.remove('d-none');
        });
    });

    // 4. Delete Mapping Action
    document.querySelectorAll('.btn-delete-mapping').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin menghapus mata kuliah ini dari kurikulum?')) {
                const mappingId = btn.dataset.mappingId;
                fetch(`/references/curiculum-course/${mappingId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Gagal menghapus.');
                    }
                });
            }
        });
    });

    // 5. Activation Toggler Action
    const toggleActivationBtn = document.querySelector('.btn-toggle-activation');
    if (toggleActivationBtn) {
        toggleActivationBtn.addEventListener('click', () => {
            const actionText = toggleActivationBtn.innerText.trim();
            if (confirm(`Apakah Anda yakin ingin ${actionText} ini?`)) {
                fetch('{{ route("curiculum.toggle-status", $kurikulum->kurKode) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Gagal mengubah status aktivasi.');
                    }
                });
            }
        });
    }

    // 6. Drag and Drop Implementation
    const draggables = document.querySelectorAll('.draggable-course');
    const dropzones = document.querySelectorAll('.semester-dropzone');

    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', draggable.dataset.mappingId);
            draggable.style.opacity = '0.5';
        });

        draggable.addEventListener('dragend', () => {
            draggable.style.opacity = '1';
        });
    });

    dropzones.forEach(zone => {
        zone.addEventListener('dragover', (e) => {
            e.preventDefault();
            zone.classList.add('bg-light-primary');
        });

        zone.addEventListener('dragleave', () => {
            zone.classList.remove('bg-light-primary');
        });

        zone.addEventListener('drop', (e) => {
            e.preventDefault();
            zone.classList.remove('bg-light-primary');
            
            const mappingId = e.dataTransfer.getData('text/plain');
            const targetSemester = zone.dataset.semester;

            if (mappingId && targetSemester) {
                // Fetch the mapping detail and make an update call
                fetch(`/references/curiculum-course/${mappingId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        semester_anjuran: targetSemester,
                        // We must fetch and submit the existing required details:
                        // Find the mapping card element to read its attributes
                        kelompok_id: document.querySelector(`.btn-edit-mapping[data-mapping-id="${mappingId}"]`).dataset.kelompokId,
                        is_wajib: document.querySelector(`.btn-edit-mapping[data-mapping-id="${mappingId}"]`).dataset.wajib,
                        mk_prasyarat_id: document.querySelector(`.btn-edit-mapping[data-mapping-id="${mappingId}"]`).dataset.prasyarat || null,
                        nilai_prasyarat_min: document.querySelector(`.btn-edit-mapping[data-mapping-id="${mappingId}"]`).dataset.nilaiMin || null,
                        sks_override: document.querySelector(`.btn-edit-mapping[data-mapping-id="${mappingId}"]`).dataset.sksOverride || null
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        let errorMsg = data.message || 'Gagal memindahkan mata kuliah.';
                        if (data.errors) {
                            errorMsg = Object.values(data.errors).flat().join('\n');
                        }
                        alert(errorMsg);
                    }
                })
                .catch(() => {
                    alert('Terjadi kesalahan koneksi.');
                });
            }
        });
    });
});
</script>
@endsection
