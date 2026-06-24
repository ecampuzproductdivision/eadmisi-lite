@extends('layouts.app')

@section('content')
@component('components.data-page-layout')
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'CRM Leads', 'active' => true],
    ])
    @slot('title', 'CRM Leads')
    @slot('description', 'Kelola prospek dari formulir "Tanya Dulu" landing page.')
    @slot('filters')
        <div class="col-md-4 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama/WA..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="New" {{ request('status') == 'New' ? 'selected' : '' }}>New</option>
                <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Responded" {{ request('status') == 'Responded' ? 'selected' : '' }}>Responded</option>
                <option value="Converted" {{ request('status') == 'Converted' ? 'selected' : '' }}>Converted</option>
            </select>
        </div>
        <div class="col-md-2 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('crm-leads.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('crm-leads.index') }}?export=xls'"><i class="ti ti-file-spreadsheet"></i> .xls</a>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()"><i class="ti ti-printer"></i> Print</a>
    @endslot
    @slot('table')
        {{-- Overview: Stat Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card border-1 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 48px; height: 48px; background: #667eea15;"><i class="ti ti-users fs-4" style="color: #667eea;"></i></div>
                            <div><p class="text-muted small mb-0">Total Leads Masuk</p><h3 class="fw-bold mb-0">{{ $totalLeads }}</h3></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-1 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 48px; height: 48px; background: #dc262615;"><i class="ti ti-alert-circle fs-4" style="color: #dc2626;"></i></div>
                            <div><p class="text-muted small mb-0">Belum Ditangani (New)</p><h3 class="fw-bold mb-0 text-danger">{{ $newLeads }}</h3></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-1 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 48px; height: 48px; background: #d9770615;"><i class="ti ti-phone-call fs-4" style="color: #d97706;"></i></div>
                            <div><p class="text-muted small mb-0">Sedang Dihubungi</p><h3 class="fw-bold mb-0 text-warning">{{ $inProgressLeads }}</h3></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-1 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 48px; height: 48px; background: #05966915;"><i class="ti ti-circle-check fs-4" style="color: #059669;"></i></div>
                            <div><p class="text-muted small mb-0">Berhasil Konversi</p><h3 class="fw-bold mb-0 text-success">{{ $convertedLeads }}</h3></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-hover align-middle mb-0 table-ead">
            <thead class="table-light">
                <tr>
                    <th class="text-center py-3" style="width:50px;">No</th>
                    <th class="py-3">Tanggal Masuk</th>
                    <th class="py-3">Nama Prospek</th>
                    <th class="py-3">WhatsApp</th>
                    <th class="py-3">Pertanyaan</th>
                    <th class="py-3">Status</th>
                    <th class="text-center py-3">Aksi</th>
                </tr>
            </thead>
            <tbody id="leads-table-body">
                @if($leads->isEmpty())
                    <tr><td colspan="7" class="text-center py-5"><i class="ti ti-inbox-off text-muted fs-1"></i><p class="mt-3 mb-0 text-muted">Belum ada lead.</p></td></tr>
                @else
                    @include('crm-leads.partials.lead_rows')
                @endif
            </tbody>
        </table>
        @if($leads->hasPages())
            <div class="card-footer bg-white border-0 py-3">{{ $leads->links() }}</div>
        @endif
    @endslot
@endcomponent

<!-- Detail & Catatan Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title fw-bold text-white"><i class="ti ti-user-circle me-2"></i>Detail & Catatan Lead</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="detailContent"><div class="text-center py-4"><div class="spinner-border text-primary"></div></div></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openDetail(id) {
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    document.getElementById('detailContent').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
    modal.show();
    fetch(`/crm-leads/${id}`).then(r => r.json()).then(lead => {
        let html = `<div class="row g-4">`;
        html += `<div class="col-md-6"><div class="border rounded p-3"><small class="text-muted text-uppercase fw-semibold">Nama Prospek</small><h5 class="fw-bold mt-1 mb-0">${lead.nama}</h5></div></div>`;
        html += `<div class="col-md-6"><div class="border rounded p-3"><small class="text-muted text-uppercase fw-semibold">WhatsApp</small><h5 class="fw-bold mt-1 mb-0"><a href="https://wa.me/${lead.whatsapp}?text=Halo%20${encodeURIComponent(lead.nama)},%20saya%20Admin%20PMB..." target="_blank" class="text-success text-decoration-none"><i class="ti ti-brand-whatsapp me-1"></i>${lead.whatsapp}</a></h5></div></div>`;
        html += `<div class="col-12"><div class="border rounded p-3"><small class="text-muted text-uppercase fw-semibold">Pertanyaan</small><p class="mt-2 mb-0">${lead.pertanyaan}</p></div></div>`;
        html += `<div class="col-12"><div class="border rounded p-3"><small class="text-muted text-uppercase fw-semibold">Status Saat Ini</small><div class="mt-2"><span class="badge bg-${lead.status === 'New' ? 'danger' : lead.status === 'In Progress' ? 'warning' : lead.status === 'Responded' ? 'success' : 'primary'}-subtle text-${lead.status === 'New' ? 'danger' : lead.status === 'In Progress' ? 'warning' : lead.status === 'Responded' ? 'success' : 'primary'} px-3 py-2 fs-6">${lead.status}</span></div></div></div>`;
        html += `<div class="col-12"><div class="border rounded p-3"><small class="text-muted text-uppercase fw-semibold">Catatan Admin</small><textarea id="catatan_admin" class="form-control mt-2" rows="3" placeholder="Tulis catatan follow-up...">${lead.catatan_admin || ''}</textarea><button class="btn btn-primary btn-sm mt-2" onclick="saveNotes(${lead.id})"><i class="ti ti-device-floppy me-1"></i> Simpan Catatan</button><div id="notesFeedback" class="small mt-1"></div></div></div>`;
        html += `<div class="col-12"><div class="border rounded p-3"><span class="text-muted text-uppercase fw-semibold">Informasi Sistem</span><div class="row mt-2"><div class="col-md-6"><span class="text-muted">Masuk:</span><p class="mb-0">${new Date(lead.created_at).toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit' })}</p></div><div class="col-md-6"><span class="text-muted">Diperbarui:</span><p class="mb-0">${new Date(lead.updated_at).toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit' })}</p></div></div></div></div></div>`;
        document.getElementById('detailContent').innerHTML = html;
    }).catch(() => { document.getElementById('detailContent').innerHTML = '<div class="alert alert-danger">Gagal memuat data.</div>'; });
}

function saveNotes(id) {
    const catatan = document.getElementById('catatan_admin').value;
    const btn = event.target; btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
    fetch(`/crm-leads/${id}/notes`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: JSON.stringify({ catatan_admin: catatan }) })
    .then(r => r.json()).then(d => { document.getElementById('notesFeedback').innerHTML = '<span class="text-success"><i class="ti ti-check"></i> Catatan berhasil disimpan.</span>'; btn.innerHTML = '<i class="ti ti-device-floppy me-1"></i> Simpan Catatan'; btn.disabled = false; })
    .catch(() => { document.getElementById('notesFeedback').innerHTML = '<span class="text-danger">Gagal menyimpan.</span>'; btn.innerHTML = '<i class="ti ti-device-floppy me-1"></i> Simpan Catatan'; btn.disabled = false; });
}

function updateStatus(id, status) {
    fetch(`/crm-leads/${id}/status`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: JSON.stringify({ status }) })
    .then(r => r.json()).then(d => { if (d.success) location.reload(); });
}

function deleteLead(id, name) {
    if (!confirm(`Hapus lead dari ${name}?`)) return;
    fetch(`/crm-leads/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } })
    .then(r => r.json()).then(d => { if (d.success) location.reload(); });
}
</script>
@endpush
@endsection