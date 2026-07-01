{{-- 
  Reusable Data Page Layout Component
  Usage: 
--}}
<main class="p-2">
    {{-- Success/Error Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-circle-check fs-4 me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-triangle fs-4 me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{ $slot ?? '' }}

    <div class="sticky-header-filter">
        {{-- SECTION 1: Breadcrumb --}}
        @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    @foreach($breadcrumbs as $crumb)
                        @if(isset($crumb['active']) && $crumb['active'])
                            <li class="breadcrumb-item active" aria-current="page">{{ $crumb['label'] }}</li>
                        @elseif(isset($crumb['url']))
                            <li class="breadcrumb-item"><a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a></li>
                        @else
                            <li class="breadcrumb-item">{{ $crumb['label'] }}</li>
                        @endif
                    @endforeach
                </ol>
            </nav>
            <hr>
        @endif

        {{-- SECTION 2: Title Header --}}
        <div class="d-flex justify-content-between align-items-center my-5">
            <div class="d-flex align-items-top gap-3">
                @if(isset($backUrl))
                    <a href="{{ $backUrl }}" class="btn btn-light d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 36px; height: 36px;" title="{{ $backLabel ?? 'Kembali' }}">
                        <i class="ti ti-arrow-left fs-5"></i>
                    </a>
                @endif
                <div>
                    <h1 class="mb-1 fw-bold">{{ $title ?? 'Page Title' }}</h1>
                    @if(isset($description))
                        <p class="text-muted mb-0">{{ $description }}</p>
                    @endif
                </div>
            </div>
            @if(isset($actions))
                <div class="d-flex gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    </div>

    {{-- Cards section (outside main card) --}}
    @if(isset($cards))
        {{ $cards }}
    @endif

    {{-- Main Card: Card Header (filters/exports/showing info) + Card Body (scrollable table with sticky header) --}}
    @if(isset($filters) || isset($exports) || isset($table) || isset($pagination))
        <div class="card border-1 shadow-sm data-page-card">
            {{-- Card Header: Filters and Exports --}}
            @if(isset($filters) || isset($exports))
                <div class="card-header border-bottom-0 bg-transparent px-4 pt-4 pb-0">
                    <form method="GET" action="{{ url()->current() }}" class="row g-2 align-items-end data-page-filters">
                        <div class="col-md-10 col-12">
                            <div class="row g-2">
                                {{ $filters ?? '' }}
                            </div>
                        </div>
                        @if(isset($exports))
                            <div class="col-md-2 col-12 d-flex gap-2 justify-content-md-end">
                                {{ $exports }}
                            </div>
                        @endif
                    </form>
                </div>
            @endif

            {{-- Showing Info Row (in card-header as well) --}}
            @if(isset($table))
                <div class="card-header border-bottom-0 bg-transparent px-4 pt-3 pb-2">
                    @if(isset($showingInfo))
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <i class="ti ti-database me-1"></i>
                                <strong>{!! $showingInfo !!}</strong>
                            </div>
                            @if(isset($spinner))
                                <div id="{{ $spinnerId ?? 'loading-spinner' }}" class="d-none">
                                    <div class="spinner-border text-primary" role="status" style="width: 1.5rem; height: 1.5rem;">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @elseif(isset($data))
                        @php
                            $dataCount = method_exists($data, 'total') ? $data->total() : $data->count();
                            $dataShowing = $data->count();
                        @endphp
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <i class="ti ti-database me-1"></i>
                                <strong>Showing <span id="showing-count">{{ $dataShowing }}</span> from <span id="total-count">{{ $dataCount }}</span> data</strong>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Card Body: Scrollable Table with Sticky Header --}}
            @if(isset($table))
                <div class="card-body px-4 py-0 data-page-table-container data-page-table-scroll">
                    {{ $table }}
                    @if(isset($sentinel))
                        <div id="{{ $sentinelId ?? 'scroll-sentinel' }}" class="text-center py-2"></div>
                    @endif
                </div>
            @endif

            {{-- Pagination --}}
            @if(isset($pagination))
                <div class="card-footer border-top-0 bg-transparent px-4 pt-3 pb-4">
                    <div class="data-page-pagination">
                        {{ $pagination }}
                    </div>
                </div>
            @endif
        </div>
    @endif
</main>