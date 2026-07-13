@extends('layouts.app')

@section('content')
@component('components.data-page-layout')
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Landing Page', 'active' => true],
    ])
    @slot('title', 'Landing Page')
    @slot('description', 'Atur konten landing page portal PMB.')
    @slot('cards')
    {{-- Info alert --}}
    <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="ti ti-info-circle fs-4"></i>
        <div><strong>Info:</strong> Data Program Studi dan Jalur Pendaftaran pada Landing Page ditarik otomatis dari Master Data Operasional.</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <div class="card border-1 shadow-sm">
        <div class="card-header bg-transparent px-4 pt-3 pb-0 border-bottom">
            <ul class="nav nav-tabs card-header-tabs mb-0" id="landingTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active " id="banner-tab" data-bs-toggle="tab" data-bs-target="#banner" type="button" role="tab"><i class="ti ti-photo me-2"></i>Banner Utama</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link " id="features-tab" data-bs-toggle="tab" data-bs-target="#features" type="button" role="tab"><i class="ti ti-star me-2"></i>Tentang Kami</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link " id="prodi-tab" data-bs-toggle="tab" data-bs-target="#prodi" type="button" role="tab"><i class="ti ti-book me-2"></i>Pilihan Prodi</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link " id="facility-tab" data-bs-toggle="tab" data-bs-target="#facility" type="button" role="tab"><i class="ti ti-building me-2"></i>Fasilitas</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link " id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab"><i class="ti ti-address-book me-2"></i>Kontak & Sosial Media</button>
                </li>
            </ul>
        </div>
        <div class="card-body p-4">
            <div class="tab-content" id="landingTabsContent">
                <!-- TAB 1: Banner Utama -->
                <div class="tab-pane fade show active" id="banner" role="tabpanel">
                    <div class="card border shadow-sm">
                        <div class="card-header bg-transparent py-3 px-4 d-flex align-items-center">
                            <h5 class="fw-bold mb-0"><i class="ti ti-photo me-2"></i>Pengaturan Banner Utama</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('settings.landing-page.update-settings') }}" method="POST" class="row g-3">
                                @csrf
                                <div class="col-md-12"><label class="form-label ">Judul Banner</label><input type="text" name="landing_banner_title" class="form-control" value="{{ $settings['landing_banner_title']->value ?? 'Penerimaan Mahasiswa Baru TA 2026/2027' }}" maxlength="255"></div>
                                <div class="col-md-12"><label class="form-label ">Subtitle / Deskripsi</label><textarea name="landing_banner_subtitle" class="form-control" rows="2">{{ $settings['landing_banner_subtitle']->value ?? 'Bergabunglah dengan ribuan mahasiswa lainnya...' }}</textarea></div>
                                <div class="col-md-4"><label class="form-label ">Teks Tombol Utama</label><input type="text" name="landing_banner_cta_primary" class="form-control" value="{{ $settings['landing_banner_cta_primary']->value ?? 'Daftar Sekarang' }}" maxlength="100"></div>
                                <div class="col-md-4"><label class="form-label ">Teks Tombol Sekunder</label><input type="text" name="landing_banner_cta_secondary" class="form-control" value="{{ $settings['landing_banner_cta_secondary']->value ?? 'Lihat Jalur' }}" maxlength="100"></div>
                                <div class="col-12"><button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2"><i class="ti ti-device-floppy"></i> Simpan Banner</button></div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: Tentang Kami -->
                <div class="tab-pane fade" id="features" role="tabpanel">
                    <div class="card border shadow-sm mb-4">
                        <div class="card-header bg-transparent py-3 px-4 d-flex align-items-center">
                            <h5 class="fw-bold mb-0"><i class="ti ti-article me-2"></i>Pengaturan Teks Pengantar</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('settings.landing-page.update-settings') }}" method="POST" class="row g-3">
                                @csrf
                                <div class="col-md-12"><label class="form-label ">Judul Utama Section</label><input type="text" name="landing_about_title" class="form-control" value="{{ $settings['landing_about_title']->value ?? 'Mengapa Memilih Kampus Kami?' }}" maxlength="255"></div>
                                <div class="col-12"><label class="form-label ">Deskripsi Pengantar Section</label><textarea name="landing_about_description" class="form-control" rows="2">{{ $settings['landing_about_description']->value ?? 'Kami berkomitmen untuk memberikan pendidikan berkualitas...' }}</textarea></div>
                                <div class="col-12"><button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2"><i class="ti ti-device-floppy"></i> Simpan Teks Pengantar</button></div>
                            </form>
                        </div>
                    </div>
                    <div class="card border shadow-sm">
                        <div class="card-header bg-transparent py-3 px-4 d-flex align-items-center justify-content-between">
                            <h5 class="fw-bold mb-0"><i class="ti ti-list me-2"></i>Daftar Konten Tentang Kami</h5>
                            <div class="d-flex gap-2 align-items-center">
                                <button class="btn btn-primary d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addFeatureModal"><i class="ti ti-plus"></i> Tambah</button>
                            </div>
                        </div>
                        <div class="card-body data-page-table-container">
                            <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light"><tr><th class="py-3" style="width:50px;">No</th><th class="py-3">Icon</th><th class="py-3">Judul Poin</th><th class="py-3">Deskripsi Poin</th><th class="py-3">Warna</th><th class="py-3">Urutan</th><th class="py-3">Status</th><th class="py-3 text-end" style="width:150px;">Aksi</th></tr></thead>
                            <tbody>
                                @forelse($features as $f)
                                <tr>
                                    <td class="py-3">{{ $loop->iteration }}</td>
                                    <td class="py-3"><i class="ti {{ $f->nama_icon }} fs-4 text-primary"></i></td>
                                    <td class="py-3 ">{{ $f->judul_poin }}</td>
                                    <td class="py-3 text-muted">{{ Str::limit($f->deskripsi_poin, 60) }}</td>
                                    <td class="py-3"><span class="badge bg-{{ $f->warna_skema }}-subtle text-{{ $f->warna_skema }} px-2">{{ $f->warna_skema }}</span></td>
                                    <td class="py-3"><span class="badge bg-light text-dark">{{ $f->sort_order }}</span></td>
                                    <td class="py-3">@if($f->is_active)<span class="badge bg-success-subtle text-success">Aktif</span>@else<span class="badge bg-danger-subtle text-danger">Nonaktif</span>@endif</td>
                                    <td class="py-3 text-end"><div class="d-flex gap-1 justify-content-end"><button class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1" title="Edit" onclick="openEditFeature({{ $f->id }}, '{{ $f->judul_poin }}', '{{ $f->deskripsi_poin }}', '{{ $f->nama_icon }}', '{{ $f->warna_skema }}')"><i class="ti ti-edit fs-5"></i></button><a href="{{ route('settings.landing-page.toggle-feature', $f) }}" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1"><i class="ti ti-{{ $f->is_active ? 'eye-off' : 'eye' }} fs-5"></i></a><form action="{{ route('settings.landing-page.destroy-feature', $f) }}" method="POST" onsubmit="return confirmSubmit(event, 'Hapus keunggulan ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-light border text-danger d-inline-flex align-items-center gap-1"><i class="ti ti-trash fs-5"></i></button></form></div></td>
                                </tr>
                                @empty
                                <tr><td colspan="8" class="text-center py-5">@include('components.empty-state', ['icon' => 'ti-star-off', 'title' => 'Belum Ada Konten Tentang Kami', 'subtitle' => 'Klik "Tambah" untuk menambahkan poin keunggulan.'])</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: Pilihan Prodi -->
                <div class="tab-pane fade" id="prodi" role="tabpanel">
                    <div class="card border shadow-sm">
                        <div class="card-header bg-transparent py-3 px-4 d-flex align-items-center justify-content-between">
                            <h5 class="fw-bold mb-0"><i class="ti ti-book me-2"></i>Daftar Program Studi Landing Page</h5>
                            <div class="d-flex gap-2 align-items-center">
                                <button class="btn btn-primary d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addProdiModal"><i class="ti ti-plus"></i> Tambah/Atur</button>
                            </div>
                        </div>
                        <div class="card-body data-page-table-container">
                            <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light"><tr><th class="py-3" style="width:50px;">No</th><th class="py-3">Nama Prodi</th><th class="py-3">Deskripsi Singkat</th><th class="py-3">Icon</th><th class="py-3">Status Publish</th><th class="py-3 text-end" style="width:120px;">Aksi</th></tr></thead>
                            <tbody>
                                @forelse($landingProdis as $lp)
                                <tr>
                                    <td class="py-3">{{ $loop->iteration }}</td>
                                    <td class="py-3 ">{{ $lp->programStudi->nama_prodi ?? $lp->programStudi->nama ?? '-' }}</td>
                                    <td class="py-3 text-muted">{{ Str::limit($lp->deskripsi_singkat, 50) }}</td>
                                    <td class="py-3"><i class="ti {{ $lp->kode_icon }} fs-4 text-primary"></i></td>
                                    <td class="py-3"><a href="{{ route('settings.landing-page.toggle-prodi', $lp) }}" class="badge bg-{{ $lp->is_published ? 'success' : 'danger' }}-subtle text-{{ $lp->is_published ? 'success' : 'danger' }} px-3 py-2 text-decoration-none">{{ $lp->is_published ? 'Published' : 'Draft' }}</a></td>
                                    <td class="py-3 text-end"><form action="{{ route('settings.landing-page.destroy-prodi', $lp) }}" method="POST" onsubmit="return confirmSubmit(event, 'Hapus prodi ini dari landing page?')">@csrf @method('DELETE')<button class="btn btn-sm btn-light border text-danger d-inline-flex align-items-center gap-1"><i class="ti ti-trash fs-5"></i></button></form></td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-5">@include('components.empty-state', ['icon' => 'ti-book-off', 'title' => 'Belum Ada Program Studi', 'subtitle' => 'Klik "Tambah/Atur" untuk menambahkan program studi ke landing page.'])</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>

                <!-- TAB 4: Fasilitas -->
                <div class="tab-pane fade" id="facility" role="tabpanel">
                    <div class="card border shadow-sm mb-4">
                        <div class="card-header bg-transparent py-3 px-4 d-flex align-items-center">
                            <h5 class="fw-bold mb-0"><i class="ti ti-article me-2"></i>Pengaturan Teks Pengantar</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('settings.landing-page.update-settings') }}" method="POST" class="row g-3">
                                @csrf
                                <div class="col-12"><label class="form-label">Judul Utama Section</label><input type="text" name="landing_facility_title" class="form-control" value="{{ $settings['landing_facility_title']->value ?? 'Fasilitas Unggulan' }}" maxlength="255"></div>
                                <div class="col-12"><label class="form-label">Deskripsi Pengantar Section</label><textarea name="landing_facility_description" class="form-control" rows="2">{{ $settings['landing_facility_description']->value ?? 'Nikmati berbagai fasilitas modern untuk mendukung perkuliahan Anda' }}</textarea></div>
                                <div class="col-12"><button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2"><i class="ti ti-device-floppy"></i> Simpan Teks Pengantar</button></div>
                            </form>
                        </div>
                    </div>
                    <div class="card border shadow-sm">
                        <div class="card-header bg-transparent py-3 px-4 d-flex align-items-center justify-content-between">
                            <h5 class="fw-bold mb-0"><i class="ti ti-list me-2"></i>Daftar Poin Fasilitas</h5>
                            <div class="d-flex gap-2 align-items-center">
                                <button class="btn btn-primary d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addFacilityModal"><i class="ti ti-plus"></i> Tambah Fasilitas</button>
                            </div>
                        </div>
                        <div class="card-body data-page-table-container">
                            <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light"><tr><th class="py-3" style="width:50px;">No</th><th class="py-3">Icon</th><th class="py-3">Nama Fasilitas</th><th class="py-3">Deskripsi</th><th class="py-3">Urutan</th><th class="py-3">Status</th><th class="py-3 text-end" style="width:120px;">Aksi</th></tr></thead>
                            <tbody>
                                @forelse($facilities as $f)
                                <tr>
                                    <td class="py-3">{{ $loop->iteration }}</td>
                                    <td class="py-3"><i class="ti {{ $f->kode_icon }} fs-4 text-primary"></i></td>
                                    <td class="py-3 ">{{ $f->nama_fasilitas }}</td>
                                    <td class="py-3 text-muted">{{ Str::limit($f->deskripsi_fasilitas, 50) }}</td>
                                    <td class="py-3"><span class="badge bg-light text-dark">{{ $f->urutan }}</span></td>
                                    <td class="py-3"><a href="{{ route('settings.landing-page.toggle-facility', $f) }}" class="badge bg-{{ $f->is_active ? 'success' : 'danger' }}-subtle text-{{ $f->is_active ? 'success' : 'danger' }} px-3 py-2 text-decoration-none">{{ $f->is_active ? 'Aktif' : 'Nonaktif' }}</a></td>
                                    <td class="py-3 text-end"><form action="{{ route('settings.landing-page.destroy-facility', $f) }}" method="POST" onsubmit="return confirmSubmit(event, 'Hapus fasilitas ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-light border text-danger d-inline-flex align-items-center gap-1"><i class="ti ti-trash fs-5"></i></button></form></td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center py-5">@include('components.empty-state', ['icon' => 'ti-building-off', 'title' => 'Belum Ada Fasilitas', 'subtitle' => 'Klik "Tambah Fasilitas" untuk menambahkan fasilitas.'])</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>

                <!-- TAB 5: Kontak & Sosial Media -->
                <div class="tab-pane fade" id="contact" role="tabpanel">
                    <div class="card border shadow-sm">
                        <div class="card-header bg-transparent py-3 px-4 d-flex align-items-center">
                            <h5 class="fw-bold mb-0"><i class="ti ti-address-book me-2"></i>Kontak & Sosial Media</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('settings.landing-page.update-settings') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-4"><label class="form-label ">Email</label><div class="input-group"><span class="input-group-text"><i class="ti ti-mail"></i></span><input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email']->value ?? '' }}" required></div></div>
                            <div class="col-md-4"><label class="form-label ">Telepon</label><div class="input-group"><span class="input-group-text"><i class="ti ti-phone"></i></span><input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone']->value ?? '' }}" required></div></div>
                            <div class="col-12"><label class="form-label ">Alamat</label><div class="input-group"><span class="input-group-text"><i class="ti ti-map-pin"></i></span><input type="text" name="contact_address" class="form-control" value="{{ $settings['contact_address']->value ?? '' }}" required></div></div>
                            <div class="col-md-4"><label class="form-label ">Instagram</label><div class="input-group"><span class="input-group-text"><i class="ti ti-brand-instagram"></i></span><input type="url" name="social_instagram" class="form-control" value="{{ $settings['social_instagram']->value ?? '' }}"></div></div>
                            <div class="col-md-4"><label class="form-label ">Facebook</label><div class="input-group"><span class="input-group-text"><i class="ti ti-brand-facebook"></i></span><input type="url" name="social_facebook" class="form-control" value="{{ $settings['social_facebook']->value ?? '' }}"></div></div>
                            <div class="col-md-4"><label class="form-label ">Youtube</label><div class="input-group"><span class="input-group-text"><i class="ti ti-brand-youtube"></i></span><input type="url" name="social_youtube" class="form-control" value="{{ $settings['social_youtube']->value ?? '' }}"></div></div>
                            <div class="col-12"><button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4"><i class="ti ti-device-floppy"></i> Simpan Pengaturan</button></div>
                        </div>
                    </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endslot
@endcomponent

<!-- Modal Facility -->
<div class="modal fade" id="addFacilityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"><h5 class="modal-title fw-bold text-white"><i class="ti ti-plus me-2"></i>Tambah Fasilitas</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <form action="{{ route('settings.landing-page.store-facility') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3"><label class="form-label ">Nama Fasilitas <span class="text-danger">*</span></label><input type="text" name="nama_fasilitas" class="form-control" required maxlength="255" placeholder="Contoh: WiFi 24 Jam"></div>
                    <div class="mb-3"><label class="form-label ">Deskripsi Fasilitas</label><textarea name="deskripsi_fasilitas" class="form-control" rows="2" placeholder="Deskripsi fasilitas..."></textarea></div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6"><label class="form-label ">Kode Icon</label><select name="kode_icon" class="form-select" required>@foreach($facilityIcons as $ico)<option value="{{ $ico }}" {{ $loop->first ? 'selected' : '' }}>{{ $ico }}</option>@endforeach</select></div>
                        <div class="col-md-6"><label class="form-label ">Urutan</label><input type="number" name="urutan" class="form-control" value="0" min="0"></div>
                    </div>
                    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_active" id="facility_active" value="1" checked><label class="form-check-label " for="facility_active">Aktif</label></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Feature -->
<div class="modal fade" id="addFeatureModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"><h5 class="modal-title fw-bold text-white"><i class="ti ti-plus me-2"></i>Tambah Keunggulan</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <form action="{{ route('settings.landing-page.store-feature') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3"><label class="form-label ">Icon</label><select name="nama_icon" class="form-select" required>@foreach($iconOptions as $ico)<option value="{{ $ico }}">{{ $ico }}</option>@endforeach</select></div>
                    <div class="mb-3"><label class="form-label ">Warna Skema</label><select name="warna_skema" class="form-select"><option value="danger">Merah (danger)</option><option value="success">Hijau (success)</option><option value="info">Biru Muda (info)</option><option value="warning">Kuning (warning)</option><option value="primary">Biru (primary)</option><option value="secondary">Abu (secondary)</option></select></div>
                    <div class="mb-3"><label class="form-label ">Judul Poin <span class="text-danger">*</span></label><input type="text" name="judul_poin" class="form-control" required maxlength="255" placeholder="Contoh: Terakreditasi Unggul"></div>
                    <div class="mb-3"><label class="form-label ">Deskripsi Poin <span class="text-danger">*</span></label><textarea name="deskripsi_poin" class="form-control" rows="3" required placeholder="Deskripsi singkat..."></textarea></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Feature -->
<div class="modal fade" id="editFeatureModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"><h5 class="modal-title fw-bold text-white"><i class="ti ti-edit me-2"></i>Edit Keunggulan</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <form id="editFeatureForm" method="POST">@csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3"><label class="form-label ">Icon</label><select name="nama_icon" id="edit_icon" class="form-select" required>@foreach($iconOptions as $ico)<option value="{{ $ico }}">{{ $ico }}</option>@endforeach</select></div>
                    <div class="mb-3"><label class="form-label ">Warna Skema</label><select name="warna_skema" id="edit_warna" class="form-select"><option value="danger">Merah (danger)</option><option value="success">Hijau (success)</option><option value="info">Biru Muda (info)</option><option value="warning">Kuning (warning)</option><option value="primary">Biru (primary)</option><option value="secondary">Abu (secondary)</option></select></div>
                    <div class="mb-3"><label class="form-label ">Judul Poin <span class="text-danger">*</span></label><input type="text" name="judul_poin" id="edit_title" class="form-control" required maxlength="255"></div>
                    <div class="mb-3"><label class="form-label ">Deskripsi Poin <span class="text-danger">*</span></label><textarea name="deskripsi_poin" id="edit_description" class="form-control" rows="3" required></textarea></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Perbarui</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Prodi Landing -->
<div class="modal fade" id="addProdiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"><h5 class="modal-title fw-bold text-white"><i class="ti ti-plus me-2"></i>Tambah/Atur Prodi</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <form action="{{ route('settings.landing-page.store-prodi') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3"><label class="form-label ">Pilih Program Studi <span class="text-danger">*</span></label><select name="program_studi_id" class="form-select" required><option value="">Pilih prodi...</option>@foreach($allProgramStudis as $prodi)<option value="{{ $prodi->id }}">{{ $prodi->nama_prodi ?: $prodi->nama }} ({{ $prodi->jenjang_akademik ?? $prodi->jenjang ?? 'S1' }})</option>@endforeach</select></div>
                    <div class="row g-3 mb-3"><div class="col-md-6"><label class="form-label ">Akreditasi</label><input type="text" name="akreditasi" class="form-control" placeholder="Contoh: Unggul"></div><div class="col-md-6"><label class="form-label ">Jumlah Semester</label><input type="number" name="jumlah_semester" class="form-control" placeholder="Contoh: 8" min="1" max="12"></div></div>
                    <div class="mb-3"><label class="form-label ">Deskripsi Singkat Marketing</label><textarea name="deskripsi_singkat" class="form-control" rows="3" placeholder="Deskripsi untuk landing page..."></textarea></div>
                    <div class="mb-3"><label class="form-label ">Kode Icon Layout</label><select name="kode_icon" class="form-select" required>@foreach($iconOptionsProdi as $ico)<option value="{{ $ico }}">{{ $ico }}</option>@endforeach</select></div>
                    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_published" id="prodi_publish" value="1" checked><label class="form-check-label " for="prodi_publish">Publish ke Landing Page</label></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan</button></div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openEditFeature(id, judul, desc, icon, warna) {
  document.getElementById('edit_title').value = judul;
  document.getElementById('edit_description').value = desc;
  document.getElementById('edit_icon').value = icon;
  document.getElementById('edit_warna').value = warna || 'danger';
  document.getElementById('editFeatureForm').action = '/settings/landing-page/feature/' + id;
  new bootstrap.Modal(document.getElementById('editFeatureModal')).show();
}
</script>
@endpush
@endsection
