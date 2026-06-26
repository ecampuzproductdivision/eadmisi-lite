@extends('layouts.app')

@section('content')
<main class="p-2">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pages.index') }}">Settings</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pages.index') }}">Pages</a></li>
            <li class="breadcrumb-item active">Register New Page</li>
        </ol>
    </nav>
    <hr>

    {{-- Title with Back Button --}}
    <div class="d-flex align-items-top gap-3 my-5">
        <a href="{{ route('pages.index') }}" class="btn btn-light d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 36px; height: 36px;" title="Back to List">
            <i class="ti ti-arrow-left fs-5"></i>
        </a>
        <div>
            <h1 class="mb-1 fw-bold">Register New Page</h1>
            <p class="text-muted mb-0">Register a new system view or functional screen to assign dynamic permissions.</p>
        </div>
    </div>

    {{-- Card Form --}}
    <div class="card border-1 shadow-sm px-4 py-4">
        <div class="col-xl-12 col-12">
            <form action="{{ route('pages.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                @if($errors->any())
                    <div class="alert alert-danger mb-4 py-2 small">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3">
                    {{-- Row 1: Page Name, Page Code, Status --}}
                    <div class="col-md-4 col-12">
                        <label for="page_name" class="form-label">Page Name <span class="text-danger">*</span></label>
                        <input type="text" name="page_name" id="page_name" class="form-control" placeholder="e.g. Sales Report" value="{{ old('page_name') }}" required>
                        <div class="invalid-feedback">Please enter the page name.</div>
                    </div>

                    <div class="col-md-4 col-12">
                        <label for="page_code" class="form-label">Page Code <span class="text-danger">*</span></label>
                        <input type="text" name="page_code" id="page_code" class="form-control" placeholder="e.g. PAGE_SALES_REPORT" value="{{ old('page_code') }}" required>
                        <small class="text-muted">Must be unique, uppercase, with no spaces.</small>
                        <div class="invalid-feedback">Please enter a unique uppercase page code.</div>
                    </div>

                    <div class="col-md-4 col-12">
                        <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="is_active" id="is_active" class="form-select" required>
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active (Enforce permissions)</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Disabled (Bypass checks)</option>
                        </select>
                        <div class="invalid-feedback">Please select the page status.</div>
                    </div>

                    {{-- Row 2: Parent Menu, Route Path, Component Name --}}
                    <div class="col-md-4 col-12">
                        <label for="menu_id" class="form-label">Associated Sidebar Menu <small class="text-muted">(Optional)</small></label>
                        <select name="menu_id" id="menu_id" class="form-select">
                            <option value="">None / Standalone Page</option>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}" {{ old('menu_id') == $menu->id ? 'selected' : '' }}>{{ $menu->menu_name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Used to render this page in a specific navigation category.</small>
                    </div>

                    <div class="col-md-4 col-12">
                        <label for="route_path" class="form-label">Route Path / URI <small class="text-muted">(Optional)</small></label>
                        <input type="text" name="route_path" id="route_path" class="form-control" placeholder="e.g. /sales/report" value="{{ old('route_path') }}">
                        <small class="text-muted">Required if you want the permission middleware to dynamically authorize this path.</small>
                    </div>

                    <div class="col-md-4 col-12">
                        <label for="component_name" class="form-label">Component / Controller <small class="text-muted">(Optional)</small></label>
                        <input type="text" name="component_name" id="component_name" class="form-control" placeholder="e.g. SalesReportController" value="{{ old('component_name') }}">
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="col-12 d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('pages.index') }}" class="btn btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Register Page</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('page_name').addEventListener('input', function() {
    const nameVal = this.value;
    const codeInput = document.getElementById('page_code');
    codeInput.value = 'PAGE_' + nameVal.toUpperCase().replace(/[^A-Z0-9]/g, '_');
  });
});
</script>
@endsection