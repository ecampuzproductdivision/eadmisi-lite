@extends('layouts.app')

@section('content')
<main class="p-2">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">Settings</a></li>
            <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">Menus</a></li>
            <li class="breadcrumb-item active">Edit Navigation Menu</li>
        </ol>
    </nav>
    <hr>

    {{-- Title with Back Button --}}
    <div class="d-flex align-items-top gap-3 my-5">
        <a href="{{ route('menus.index') }}" class="btn btn-light d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 36px; height: 36px;" title="Back to List">
            <i class="ti ti-arrow-left fs-5"></i>
        </a>
        <div>
            <h1 class="mb-1 fw-bold">Edit Navigation Menu</h1>
            <p class="text-muted mb-0">Update details for menu item: <strong>{{ $menu->menu_name }}</strong>.</p>
        </div>
    </div>

    {{-- Card Form --}}
    <div class="card border-1 shadow-sm px-4 py-4">
        <div class="col-xl-12 col-12">
            <form action="{{ route('menus.update', $menu->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

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
                    {{-- Section: Menu Information --}}
                    <div class="col-12">
                        <h6 class="text-secondary fw-bold pb-2 mb-0" style="border-bottom: 1px dashed #dee2e6;">Menu Information</h6>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="menu_name" class="form-label">Menu Name <span class="text-danger">*</span></label>
                        <input type="text" name="menu_name" id="menu_name" class="form-control" placeholder="e.g. Invoicing" value="{{ old('menu_name', $menu->menu_name) }}" required>
                        <div class="invalid-feedback">Please enter the menu name.</div>
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="menu_code" class="form-label">Menu Code <span class="text-danger">*</span></label>
                        <input type="text" name="menu_code" id="menu_code" class="form-control" placeholder="e.g. INVOICING" value="{{ old('menu_code', $menu->menu_code) }}" required>
                        <div class="invalid-feedback">Please enter a unique menu code.</div>
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="is_active" id="is_active" class="form-select" required>
                            <option value="1" {{ old('is_active', $menu->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Show in Sidebar</option>
                            <option value="0" {{ old('is_active', $menu->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Hide</option>
                        </select>
                        <div class="invalid-feedback">Please select the menu status.</div>
                    </div>

                    <div class="col-md-3 col-12"></div>

                    {{-- Section: Navigation Properties --}}
                    <div class="col-12">
                        <h6 class="text-secondary fw-bold pb-2 mb-0 mt-2" style="border-bottom: 1px dashed #dee2e6;">Navigation Properties</h6>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="parent_id" class="form-label">Parent Menu <small class="text-muted">(Optional)</small></label>
                        <select name="parent_id" id="parent_id" class="form-select">
                            <option value="">None (Root Menu)</option>
                            @foreach($parentMenus as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $menu->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->menu_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="icon" class="form-label">Icon Class <small class="text-muted">(Optional)</small></label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent"><i class="ti {{ $menu->icon ?: 'ti-settings' }} text-muted" id="icon-preview"></i></span>
                            <input type="text" name="icon" id="icon" class="form-control" placeholder="e.g. ti-settings" value="{{ old('icon', $menu->icon) }}">
                        </div>
                        <small class="text-muted">Uses Tabler Icons. Example: <code>ti-user</code>, <code>ti-coin</code>.</small>
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="url" class="form-label">URL Path</label>
                        <input type="text" name="url" id="url" class="form-control" placeholder="e.g. /finance/invoicing" value="{{ old('url', $menu->url) }}">
                        <small class="text-muted">Leave empty for folder menus that only serve as dropdown toggles.</small>
                    </div>

                    <div class="col-md-3 col-12"></div>

                    {{-- Section: Ordering --}}
                    <div class="col-12">
                        <h6 class="text-secondary fw-bold pb-2 mb-0 mt-2" style="border-bottom: 1px dashed #dee2e6;">Ordering</h6>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="sort_order" class="form-label">Sort Order <span class="text-danger">*</span></label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $menu->sort_order) }}" required>
                        <div class="invalid-feedback">Please specify the sorting weight.</div>
                    </div>

                    {{-- Submit --}}
                    <div class="col-12 d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('menus.index') }}" class="btn btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Update Menu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const iconInput = document.getElementById('icon');
  const iconPreview = document.getElementById('icon-preview');
  
  iconInput.addEventListener('input', function() {
    const val = this.value.trim();
    iconPreview.className = 'ti ' + (val ? val : 'ti-settings') + ' text-muted';
  });
});
</script>
@endsection