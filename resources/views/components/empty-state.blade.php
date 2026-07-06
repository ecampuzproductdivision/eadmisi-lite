<div class="empty-state text-center py-5">
    <div class="d-inline-flex align-items-center justify-content-center rounded-circle empty-state-icon-wrapper">
        <i class="ti {{ $icon ?? 'ti-inbox' }} text-muted empty-state-icon"></i>
    </div>
    <h6 class="mt-3 mb-1 fw-semibold empty-state-title">{{ $title ?? 'Tidak Ada Data' }}</h6>
    <p class="text-secondary mb-0 small empty-state-subtitle">{{ $subtitle ?? 'Belum ada data yang tersedia saat ini.' }}</p>
    @if(isset($action))
        <div class="mt-3">
            {{ $action }}
        </div>
    @endif
</div>