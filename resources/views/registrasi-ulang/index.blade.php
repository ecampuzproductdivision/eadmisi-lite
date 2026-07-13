@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $registrations])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Registrasi Ulang', 'active' => true],
    ])
    @slot('title', 'Registrasi Ulang')
    @slot('description', 'Tracking status registrasi ulang calon mahasiswa yang sudah lulus seleksi.')
    @slot('actions')

    @endslot
    @slot('filters')
        <div class="col-md-3 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama / NIK / NIM..." value="{{ request('search') }}">
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
            <select name="status_ulang" class="form-select">
                <option value="">Semua Status Registrasi</option>
                <option value="belum_registrasi" {{ request('status_ulang') == 'belum_registrasi' ? 'selected' : '' }}>Belum Registrasi Ulang</option>
                <option value="menunggu_pembayaran" {{ request('status_ulang') == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                <option value="sudah_registrasi_no_tagihan" {{ request('status_ulang') == 'sudah_registrasi_no_tagihan' ? 'selected' : '' }}>Sudah Registrasi (No Tagihan)</option>
                <option value="sudah_registrasi_lunas" {{ request('status_ulang') == 'sudah_registrasi_lunas' ? 'selected' : '' }}>Sudah Lunas</option>
            </select>
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-white border"><i class="ti ti-filter"></i> Filter</button>
            <a href="{{ route('registrasi-ulang.index') }}" class="btn btn-white border px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
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
                    <th class="py-3 fw-semibold">Prodi Pilihan</th>
                    <th class="py-3 fw-semibold">NIM</th>
                    <th class="py-3 fw-semibold">Status Registrasi Ulang</th>
                    <th class="pe-4 py-3 fw-semibold text-end" style="width:60px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $registration)
                @php
                    $ulangPayment = $registration->payments->first();
                @endphp
                <tr>
                    <td class="ps-4 py-3 text-muted">{{ $loop->iteration + ($registrations->currentPage() - 1) * $registrations->perPage() }}</td>
                    <td class="py-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <span class="fw-bold text-primary">{{ strtoupper(substr($registration->nama_lengkap, 0, 1)) }}</span>
                            </div>
                            <div>
                                <span class="fw-semibold">{{ $registration->nama_lengkap }}</span>
                                @if($registration->no_pendaftaran)<br><span class="text-primary small fw-bold">No. Reg: {{ $registration->no_pendaftaran }}</span>@endif
                                @if($registration->nik)<br><span class="text-muted small">NIK: {{ $registration->nik }}</span>@endif
                            </div>
                        </div>
                    </td>
                    <td class="py-3"><span class="badge bg-label-info">{{ $registration->registrationPath?->name ?? '-' }}</span></td>
                    <td class="py-3">{{ $registration->programStudi1?->nama ?? '-' }}</td>
                    <td class="py-3">{{ $registration->nim ?? '-' }}</td>
                    <td class="py-3">
                        @php
                            $label = $registration->status_registrasi_ulang_label;
                            $badge = $registration->status_registrasi_ulang_badge;
                        @endphp
                        @if($registration->status_registrasi_ulang)
                            <span class="badge {{ $badge }} rounded-pill px-3 py-1 fw-semibold">{{ $label }}</span>
                        @else
                            @if($registration->status === 'Lulus')
                                <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle rounded-pill px-3 py-1 fw-semibold">Belum Registrasi Ulang</span>
                            @elseif($registration->status === 'Menunggu Verifikasi Registrasi Ulang')
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-1 fw-semibold">Menunggu Pembayaran Registrasi Ulang</span>
                            @elseif($registration->status === 'registered')
                                <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle rounded-pill px-3 py-1 fw-semibold">Sudah Melakukan Registrasi Ulang</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill px-3 py-1 fw-semibold">{{ $registration->status }}</span>
                            @endif
                        @endif
                        @if($ulangPayment && $ulangPayment->transaction_status === 'success' && !$registration->nim)
                            <br><span class="badge bg-success-subtle text-success-emphasis border border-success-subtle mt-1" style="font-size:0.65rem;">Pembayaran Lunas</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @php
                            $dropdownItems = [
                                ['url' => route('registrasi-ulang.show', $registration->id), 'icon' => 'ti ti-eye', 'label' => 'Detail', 'title' => 'Lihat Detail Registrasi Ulang'],
                            ];
                        @endphp
                        @include('components.actions-dropdown', ['items' => $dropdownItems])
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5">@include('components.empty-state', ['icon' => 'ti-inbox', 'title' => 'Belum ada data registrasi ulang', 'subtitle' => 'Calon mahasiswa yang lulus seleksi akan muncul di sini.'])</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($registrations->hasPages())
            <div class="card-footer bg-light border-top d-flex justify-content-center py-3">{{ $registrations->appends(request()->query())->links() }}</div>
        @endif
    @endslot
@endcomponent
@endsection