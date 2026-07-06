@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $registrations])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Pendaftaran PMB', 'active' => true],
    ])
    @slot('title', 'Pendaftaran PMB')
    @slot('description', 'Data pendaftaran yang sudah disubmit oleh calon mahasiswa.')
    @slot('actions')

    @endslot
    @slot('filters')
        <div class="col-md-3 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama / NIK / No. HP..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="path_id" class="form-select">
                <option value="">Semua Jalur</option>
                @foreach($paths as $path)
                    <option value="{{ $path->id }}" {{ request('path_id') == $path->id ? 'selected' : '' }}>{{ $path->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 col-12">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="documents_uploaded" {{ request('status') == 'documents_uploaded' ? 'selected' : '' }}>Dokumen Diupload</option>
                <option value="payment_pending" {{ request('status') == 'payment_pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                <option value="payment_verified" {{ request('status') == 'payment_verified' ? 'selected' : '' }}>Pembayaran Terverifikasi</option>
                <option value="exam_completed" {{ request('status') == 'exam_completed' ? 'selected' : '' }}>Ujian Selesai</option>
                <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Direview</option>
                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Diterima</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-white border"><i class="ti ti-filter"></i> Filter</button>
            <a href="{{ route('pendaftaran.index') }}" class="btn btn-white border px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('pendaftaran.index') }}?export=xls'">
            <i class="ti ti-file-spreadsheet"></i> .xls
        </a>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
            <i class="ti ti-printer"></i> Print
        </a>
    @endslot
    @slot('table')
        <table class="table table-hover align-middle mb-0 no-sticky-global table-ead">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 fw-semibold" style="width:50px;">#</th>
                    <th class="py-3 fw-semibold">Nama Lengkap</th>
                    <th class="py-3 fw-semibold">Jalur</th>
                    <th class="py-3 fw-semibold">No. HP</th>
                    <th class="py-3 fw-semibold">Tgl Daftar</th>
                    <th class="py-3 fw-semibold">Status</th>
                    <th class="pe-4 py-3 fw-semibold text-end" style="width:60px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $registration)
                <tr>
                    <td class="ps-4 py-3 text-muted">{{ $loop->iteration + ($registrations->currentPage() - 1) * $registrations->perPage() }}</td>
                    <td class="py-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <span class="fw-bold text-primary">{{ strtoupper(substr($registration->nama_lengkap, 0, 1)) }}</span>
                            </div>
                            <div>
                                <span class="fw-semibold">{{ $registration->nama_lengkap }}</span>
                                @if($registration->nik)<br><span class="text-muted">NIK: {{ $registration->nik }}</span>@endif
                            </div>
                        </div>
                    </td>
                    <td class="py-3"><span>{{ $registration->registrationPath?->name ?? '-' }}</span></td>
                    <td class="py-3">{{ $registration->no_hp ?? '-' }}</td>
                    <td class="py-3">{{ $registration->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-3">
                        @php
                            // ═══ RESET ALL STATE VARIABLES PER ITERATION ═══
                            // CRITICAL: prevents domino effect where one row's status leaks to the next row
                            $pathObj = null;
                            $totalRequiredDocs = 0;
                            $totalUploadedDocs = 0;
                            $hasExamBeenTaken = false;
                            $isPaymentLocked = true;
                            $statusLabel = null;
                            $badgeBg = 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle';
                            $badgeText = '';
                            $subBadge = '';

                            // ── UNIFIED STATUS PIPELINE (identical to applicant portal) ──
                            $pathObj = $registration->registrationPath;

                            // Payment check — uses THIS registration's own payments (not Auth::user!)
                            $paidInvoice = $registration->payments->firstWhere('transaction_status', 'success');
                            if ($paidInvoice) $isPaymentLocked = false;

                            // Document check — uses THIS registration's own documents
                            if ($pathObj && $pathObj->templateBerkas) {
                                $totalRequiredDocs = $pathObj->templateBerkas->syaratDokumens()
                                    ->where('status_wajib', true)
                                    ->count();
                            }
                            $totalUploadedDocs = $registration->documents->count();

                            // Exam check — uses THIS registration's own exam results
                            if ($pathObj && $pathObj->is_ujian_online) {
                                $hasExamBeenTaken = $registration->examResults
                                    ->where('status', 'completed')
                                    ->isNotEmpty();
                            }

                            $isStep3Completed = ($totalRequiredDocs == 0) || ($totalUploadedDocs >= $totalRequiredDocs);

                            // Terminal states (payment_verified is NOT terminal — cascade handles it)
                            if ($registration->status === 'rejected') {
                                $badgeBg = 'bg-danger-subtle text-danger-emphasis border border-danger-subtle'; $badgeText = ''; $statusLabel = 'Ditolak'; $subBadge = '';
                            } elseif ($registration->status === 'accepted') {
                                $badgeBg = 'bg-success-subtle text-success-emphasis border border-success-subtle'; $badgeText = ''; $statusLabel = 'Diterima'; $subBadge = '';
                            } elseif ($registration->status === 'reviewed') {
                                $badgeBg = 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle'; $badgeText = ''; $statusLabel = 'Direview'; $subBadge = '';
                            } elseif ($registration->status === 'exam_completed') {
                                $badgeBg = 'bg-primary-subtle text-primary-emphasis border border-primary-subtle'; $badgeText = ''; $statusLabel = 'Ujian Selesai'; $subBadge = '';
                            } elseif ($registration->status === 'payment_pending') {
                                $isPaymentLocked = true;
                            }

                            // Cascade for unresolved statuses
                            if (!isset($statusLabel)) {
                                if ($isPaymentLocked) {
                                    $badgeBg = 'bg-danger-subtle text-danger-emphasis border border-danger-subtle'; $badgeText = ''; $statusLabel = 'Menunggu Pembayaran'; $subBadge = '';
                                } else {
                                    $subBadge = '<span class="badge bg-success-subtle text-success-emphasis border border-success-subtle mt-1 d-inline-block" style="font-size:0.65rem;">Pembayaran Terverifikasi</span>';
                                    if ($totalRequiredDocs > 0 && !$isStep3Completed) {
                                        $badgeBg = 'bg-warning-subtle text-warning-emphasis'; $badgeText = ''; $statusLabel = 'Belum Unggah Berkas';
                                    } elseif ($pathObj && $pathObj->is_ujian_online && !$hasExamBeenTaken) {
                                        $badgeBg = 'bg-info-subtle text-info-emphasis border border-info-subtle'; $badgeText = ''; $statusLabel = 'Menunggu Ujian';
                                    } else {
                                        $badgeBg = 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle'; $badgeText = ''; $statusLabel = 'Menunggu Verifikasi Berkas';
                                    }
                                }
                            }
                        @endphp
                        <span class="badge {{ $badgeBg }} rounded-pill px-3 py-1 fw-semibold">{{ $statusLabel }}</span>
                        @if(!empty($subBadge))
                            {!! $subBadge !!}
                        @endif
                    </td>
                    <td class="text-end">
                        @include('components.actions-dropdown', ['items' => [
                            ['url' => route('pendaftaran.show', $registration->id), 'icon' => 'ti ti-eye', 'label' => 'Detail', 'title' => 'Lihat Detail Pendaftaran'],
                        ]])
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5">@include('components.empty-state', ['icon' => 'ti-inbox', 'title' => 'Belum ada data pendaftaran', 'subtitle' => 'Belum ada calon mahasiswa yang melakukan submit pendaftaran.'])</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($registrations->hasPages())
            <div class="card-footer bg-light border-top d-flex justify-content-center py-3">{{ $registrations->appends(request()->query())->links() }}</div>
        @endif
    @endslot
@endcomponent
@endsection


