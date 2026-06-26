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
        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
            <i class="ti ti-users me-1"></i> Total: {{ $registrations->total() }}
        </span>
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
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Filter</button>
            <a href="{{ route('pendaftaran.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
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
                    <th class="ps-4 py-3 fw-semibold">#</th>
                    <th class="py-3 fw-semibold">Nama Lengkap</th>
                    <th class="py-3 fw-semibold">Jalur</th>
                    <th class="py-3 fw-semibold">No. HP</th>
                    <th class="py-3 fw-semibold">Tgl Daftar</th>
                    <th class="py-3 fw-semibold">Status</th>
                    <th class="pe-4 py-3 fw-semibold text-end">Aksi</th>
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
                            $statusBadge = [
                                'submitted' => ['bg-warning', 'text-dark', 'Submitted'],
                                'documents_uploaded' => ['bg-info', 'text-dark', 'Dokumen Diupload'],
                                'payment_pending' => ['bg-warning', 'text-dark', 'Menunggu Pembayaran'],
                                'payment_verified' => ['bg-success', 'text-dark', 'Pembayaran Terverifikasi'],
                                'exam_completed' => ['bg-primary', 'text-white', 'Ujian Selesai'],
                                'reviewed' => ['bg-secondary', 'text-white', 'Direview'],
                                'accepted' => ['bg-success', 'text-white', 'Diterima'],
                                'rejected' => ['bg-danger', 'text-white', 'Ditolak'],
                            ];
                            $badge = $statusBadge[$registration->status] ?? ['bg-secondary', 'text-white', $registration->status];
                        @endphp
                        <span class="badge {{ $badge[0] }} {{ $badge[1] }} rounded-pill px-3 py-1 fw-semibold">{{ $badge[2] }}</span>
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <a href="{{ route('pendaftaran.show', $registration->id) }}" class="btn btn-sm btn-outline-primary"><i class="ti ti-eye me-1"></i> Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5"><i class="ti ti-inbox text-muted" style="font-size: 3rem;"></i><span class="mt-3 text-muted fw-semibold">Belum ada data pendaftaran</span><p class="text-muted mb-0">Belum ada calon mahasiswa yang melakukan submit pendaftaran.</p></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($registrations->hasPages())
            <div class="card-footer bg-light border-top d-flex justify-content-center py-3">{{ $registrations->appends(request()->query())->links() }}</div>
        @endif
    @endslot
@endcomponent
@endsection
