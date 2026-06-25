{{-- 
  Reusable Data Page Layout Component
  Usage: 
  @component('components.data-page-layout')
      @slot('breadcrumbs', [
          ['label' => 'Home', 'url' => route('home')],
          ['label' => 'Master Data', 'url' => '#'],
          ['label' => 'Negara', 'active' => true],
      ])
      @slot('title', 'Negara')
      @slot('description', 'Kelola data negara untuk referensi alamat dan kode telepon internasional.')
      @slot('actions')
          <a href="{{ route('country.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
              <i class="ti ti-plus fs-4"></i> Tambah
          </a>
      @endslot
      @slot('filters')
          <div class="col-md-4 col-12">
              <div class="input-group">
                  <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                  <input type="text" name="search" class="form-control border-start-0" placeholder="Cari..." value="{{ request('search') }}">
              </div>
          </div>
          <div class="col-md-2 col-12">
              <select name="status" class="form-select">
                  <option value="">-- Status --</option>
                  <option value="active">Aktif</option>
                  <option value="inactive">Nonaktif</option>
              </select>
          </div>
          <div class="col-md-2 col-12 d-flex gap-2">
              <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Terapkan</button>
              <a href="{{ url()->current() }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
          </div>
      @endslot
      @slot('exports')
          <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ url()->current() }}?export=xls'">
              <i class="ti ti-file-spreadsheet"></i> .xls
          </a>
          <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
              <i class="ti ti-printer"></i> Print
          </a>
      @endslot
      @slot('table')
          @include('references.country.partials.country_rows')
      @endslot
  @endcomponent
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
        @endif

        {{-- SECTION 2: Title Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-1 fw-bold">{{ $title ?? 'Page Title' }}</h1>
                @if(isset($description))
                    <p class="text-muted mb-0">{{ $description }}</p>
                @endif
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

    {{-- Main Card: Filters + Table (merged) --}}
    <div class="card border-1 shadow-sm px-4 py-4 data-page-card">
        {{-- Filter & Export Row (Non-scrollable) --}}
        <form method="GET" action="{{ url()->current() }}" class="row g-2 align-items-end mb-4 data-page-filters">
            <div class="col-md-8 col-12">
                <div class="row g-2">
                    {{ $filters ?? '' }}
                </div>
            </div>
            @if(isset($exports))
                <div class="col-md-4 col-12 d-flex gap-2 justify-content-md-end">
                    {{ $exports }}
                </div>
            @endif
        </form>

        {{-- Table Content (Scrollable) --}}
        @if(isset($table))
            <div class="table-responsive data-page-table-scroll">
                @if(isset($showingInfo))
                    <div class="showing-info-row d-flex align-items-center justify-content-between">
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
                    <div class="showing-info-row d-flex align-items-center justify-content-between">
                        <div>
                            <i class="ti ti-database me-1"></i>
                            <strong>Showing <span id="showing-count">{{ $dataShowing }}</span> from <span id="total-count">{{ $dataCount }}</span> data</strong>
                        </div>
                    </div>
                @endif
                {{ $table }}
                @if(isset($sentinel))
                    <div id="{{ $sentinelId ?? 'scroll-sentinel' }}" class="text-center py-2"></div>
                @endif
            </div>
        @else
            <p class="text-muted mb-0 text-center py-4">No table content provided.</p>
        @endif

        {{-- Pagination --}}
        @if(isset($pagination))
            <div class="mt-3 data-page-pagination">
                {{ $pagination }}
            </div>
        @endif
    </div>
</main>