@extends('layouts.app')

@section('content')
<main class="p-6">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('registrasi-ulang.index') }}">Registrasi Ulang</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Registrasi Ulang</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar avatar-md bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <span class="fw-bold text-primary fs-5">{{ strtoupper(substr($registration->nama_lengkap, 0, 1)) }}</span>
            </div>
            <div>
                <h4 class="fw-bold mb-1">Detail Registrasi Ulang</h4>
                <p class="text-muted small mb-0">PDDIKTI & EMIS — data lengkap calon mahasiswa lulus seleksi</p>
            </div>
        </div>
        <a href="{{ route('registrasi-ulang.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="ti ti-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-circle-check fs-4 me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- ═══ LEFT COLUMN: Profile & Status ═══ -->
        <div class="col-lg-4">
            <div class="card border shadow-sm h-100">
                <div class="card-body text-center p-5">
                    <div class="avatar avatar-xl bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px;">
                        <span class="fw-bold text-primary fs-2">{{ strtoupper(substr($registration->nama_lengkap, 0, 1)) }}</span>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $registration->nama_lengkap }}</h5>
                    <p class="text-muted mb-3">{{ $registration->registrationPath?->name ?? '-' }}</p>
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        @if($registration->status_registrasi_ulang)
                            <span class="badge {{ $registration->status_registrasi_ulang_badge }} rounded-pill px-3 py-1 fw-semibold">{{ $registration->status_registrasi_ulang_label }}</span>
                        @else
                            @if($registration->status === 'Lulus')
                                <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill px-3 py-1">Belum Registrasi Ulang</span>
                            @elseif($registration->status === 'Menunggu Verifikasi Registrasi Ulang')
                                <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-1">Menunggu Pembayaran Registrasi Ulang</span>
                            @elseif($registration->status === 'registered')
                                <span class="badge bg-success-subtle text-success-emphasis rounded-pill px-3 py-1">Terdaftar</span>
                            @endif
                        @endif
                    </div>
                    <hr class="my-4">
                    <div class="text-start small">
                        <div class="mb-2"><span class="text-muted d-block">Akun</span><span class="fw-semibold">{{ $registration->user?->name ?? '-' }}</span></div>
                        <div class="mb-2"><span class="text-muted d-block">Email</span><span class="fw-semibold">{{ $registration->user?->email ?? '-' }}</span></div>
                        <div class="mb-2"><span class="text-muted d-block">Tgl Daftar</span><span class="fw-semibold">{{ $registration->created_at->format('d/m/Y H:i') }}</span></div>
                        @if($registration->re_registration_submitted_at)
                        <div class="mb-2"><span class="text-muted d-block">Reg. Ulang Disubmit</span><span class="fw-semibold">{{ \Carbon\Carbon::parse($registration->re_registration_submitted_at)->format('d/m/Y H:i') }}</span></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ RIGHT COLUMN: PDDIKTI 16-field Profile ═══ -->
        <div class="col-lg-8">
            <div class="card border shadow-sm">
                <div class="card-header bg-warning-subtle d-flex align-items-center gap-2 py-3 border-bottom">
                    <i class="ti ti-id-badge text-warning fs-4"></i>
                    <h6 class="fw-bold mb-0 text-warning-emphasis">Data Registrasi Ulang (PDDIKTI & EMIS Sync) — 16 Field</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="small text-muted mb-1 fw-semibold">Nama Lengkap</label><p class="fw-bold mb-0 fs-6">{{ $registration->nama_lengkap }}</p></div>
                        <div class="col-md-6"><label class="small text-muted mb-1 fw-semibold">Jenis Kelamin</label><p class="fw-semibold mb-0">{{ $registration->jenis_kelamin == 'L' ? 'Laki-laki' : ($registration->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</p></div>
                        <div class="col-md-6"><label class="small text-muted mb-1 fw-semibold">Tempat Lahir</label><p class="fw-semibold mb-0">{{ $registration->tempat_lahir ?? '-' }}</p></div>
                        <div class="col-md-6"><label class="small text-muted mb-1 fw-semibold">Tanggal Lahir</label><p class="fw-semibold mb-0">{{ $registration->tanggal_lahir ? \Carbon\Carbon::parse($registration->tanggal_lahir)->format('d/m/Y') : '-' }}</p></div>
                        <div class="col-md-6"><label class="small text-muted mb-1 fw-semibold">Agama</label><p class="fw-semibold mb-0">{{ $registration->agama ?? '-' }}</p></div>
                        <div class="col-md-6"><label class="small text-muted mb-1 fw-semibold">NIK <span class="text-danger">*16 digit</span></label><p class="fw-semibold mb-0">{{ $registration->nik ?? '-' }}</p></div>
                        <div class="col-md-6"><label class="small text-muted mb-1 fw-semibold">NISN <span class="text-danger">*10 digit</span></label><p class="fw-semibold mb-0">{{ $registration->nisn ?? '-' }}</p></div>
                        <div class="col-md-6"><label class="small text-muted mb-1 fw-semibold">Nama Ibu Kandung</label><p class="fw-semibold mb-0">{{ $registration->nama_ibu_kandung ?? '-' }}</p></div>
                        <div class="col-md-6"><label class="small text-muted mb-1 fw-semibold">Penerima KPS</label><p class="fw-semibold mb-0">{{ $registration->penerima_kps ?? '-' }}</p></div>
                        <div class="col-md-6"><label class="small text-muted mb-1 fw-semibold">Kebutuhan Khusus</label><p class="fw-semibold mb-0">{{ $registration->kebutuhan_khusus ?? '-' }}</p></div>
                        <div class="col-md-6"><label class="small text-muted mb-1 fw-semibold">Kewarganegaraan</label><p class="fw-semibold mb-0">{{ $registration->kewarganegaraan ?? '-' }}</p></div>
                        <div class="col-md-6"><label class="small text-muted mb-1 fw-semibold">Kabupaten / Kota</label><p class="fw-semibold mb-0">{{ $regencyName }}</p></div>
                        <div class="col-md-6"><label class="small text-muted mb-1 fw-semibold">Kecamatan</label><p class="fw-semibold mb-0">{{ $kecamatanName }}</p></div>
                        <div class="col-md-6"><label class="small text-muted mb-1 fw-semibold">Kelurahan / Desa</label><p class="fw-semibold mb-0">{{ $kelurahanName }}</p></div>
                        <div class="col-md-6"><label class="small text-muted mb-1 fw-semibold">No. Handphone</label><p class="fw-semibold mb-0">{{ $registration->no_hp ?? '-' }}</p></div>
                        <div class="col-md-6"><label class="small text-muted mb-1 fw-semibold">Alamat Email</label><p class="fw-semibold mb-0">{{ $registration->email ?? '-' }}</p></div>
                    </div>
                </div>
            </div>

            <!-- ═══ PAYMENT INFO ═══ -->
            <div class="card border shadow-sm mt-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="ti ti-receipt me-1"></i> Informasi Pembayaran Registrasi Ulang</h6>
                </div>
                <div class="card-body">
                    @php $ulangPayment = $registration->payments->first(); @endphp
                    @if($ulangPayment)
                        <div class="row g-3">
                            <div class="col-md-3"><small class="text-muted d-block">No. Invoice</small><span class="fw-semibold">{{ $ulangPayment->invoice_number }}</span></div>
                            <div class="col-md-3"><small class="text-muted d-block">Jumlah</small><span class="fw-semibold">Rp {{ number_format($ulangPayment->amount, 0, ',', '.') }}</span></div>
                            <div class="col-md-3">
                                <small class="text-muted d-block">Status</small>
                                @if($ulangPayment->transaction_status === 'success')
                                    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">Lunas</span>
                                @elseif($ulangPayment->transaction_status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Pending</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle">{{ $ulangPayment->transaction_status }}</span>
                                @endif
                            </div>
                            <div class="col-md-3"><small class="text-muted d-block">Tgl Dibuat</small><span class="fw-semibold">{{ $ulangPayment->created_at->format('d/m/Y H:i') }}</span></div>
                            @if($ulangPayment->paid_at)
                            <div class="col-md-3"><small class="text-muted d-block">Tgl Lunas</small><span class="fw-semibold">{{ $ulangPayment->paid_at->format('d/m/Y H:i') }}</span></div>
                            @endif
                            @if($ulangPayment->payment_method)
                            <div class="col-md-3"><small class="text-muted d-block">Metode</small><span class="fw-semibold">{{ $ulangPayment->payment_method }}</span></div>
                            @endif
                        </div>
                    @elseif($registration->status_registrasi_ulang === 'sudah_registrasi_no_tagihan')
                        <div class="alert alert-info border-0 shadow-none mb-0"><i class="ti ti-info-circle me-2"></i>Registrasi ulang selesai tanpa tagihan (biaya Rp 0 — KIP Kuliah / bebas biaya).</div>
                    @else
                        <p class="text-muted mb-0">
                            @if(in_array($registration->status_registrasi_ulang, ['belum_registrasi', null]) && $registration->status === 'Lulus')
                                Calon mahasiswa ini sudah lulus seleksi namun belum melakukan registrasi ulang.
                            @else
                                Belum ada tagihan registrasi ulang.
                            @endif
                        </p>
                    @endif
                </div>
            </div>

            <!-- ═══ APPROVE OR COMPLETED ═══ -->
            @if($registration->status === 'Menunggu Verifikasi Registrasi Ulang' && !$registration->nim && $registration->status_registrasi_ulang === 'sudah_registrasi_lunas')
            <div class="card border shadow-sm border-primary mt-4">
                <div class="card-header bg-primary bg-opacity-10 text-primary">
                    <h6 class="fw-bold mb-0"><i class="ti ti-certificate me-1"></i> Setujui Registrasi Ulang & Generate NIM</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-success border-0 shadow-none mb-3"><i class="ti ti-circle-check me-2"></i>Pembayaran registrasi ulang telah lunas. Silakan setujui dan generate NIM untuk mahasiswa ini.</div>
                    <form action="{{ route('registrasi-ulang.approve', $registration->id) }}" method="POST" class="row g-3 align-items-end">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor Induk Mahasiswa (NIM) <span class="text-danger">*</span></label>
                            <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror" placeholder="Masukkan NIM" required maxlength="20" value="{{ old('nim', $registration->nim) }}">
                            @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">NIM akan digunakan sebagai identitas mahasiswa selama studi.</small>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary px-5"><i class="ti ti-circle-check me-1"></i> Setujui & Generate NIM</button>
                        </div>
                    </form>
                </div>
            </div>
            @elseif($registration->status === 'Menunggu Verifikasi Registrasi Ulang' && !$registration->nim && $registration->status_registrasi_ulang === 'menunggu_pembayaran')
            <div class="card border shadow-sm border-warning mt-4">
                <div class="card-header bg-warning bg-opacity-10 text-warning-emphasis">
                    <h6 class="fw-bold mb-0"><i class="ti ti-clock me-1"></i> Menunggu Pembayaran Registrasi Ulang</h6>
                </div>
                <div class="card-body"><p class="mb-0">Calon mahasiswa telah mengirim data registrasi ulang dan menunggu pembayaran. Setelah pembayaran lunas, form persetujuan & generate NIM akan muncul di sini.</p></div>
            </div>
            @elseif($registration->nim)
            <div class="card border shadow-sm border-success mt-4">
                <div class="card-body text-center py-4">
                    <i class="ti ti-circle-check text-success fs-1 mb-2 d-block"></i>
                    <h5 class="fw-bold text-success mb-1">Registrasi Ulang Selesai</h5>
                    <p class="mb-0">Mahasiswa telah terdaftar dengan NIM: <strong class="fs-5">{{ $registration->nim }}</strong></p>
                </div>
            </div>
            @endif
        </div>
    </div>
</main>
@endsection