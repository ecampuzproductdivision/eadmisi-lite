# eAdmisi Lite — Design Rules & Component Standards

Dokumen ini mendokumentasikan aturan dan standar komponen serta struktur halaman yang harus diterapkan secara konsisten di seluruh project eAdmisi Lite.

---

## 1. Layout Structure

### 1.1 App Layout (`layouts/app.blade.php`)

```
<body>
  <div>
    @include('layouts.menu')       <!-- Sidebar -->
    <div id="content">
      @include('layouts.header')   <!-- Top navbar -->
      @yield('content')            <!-- Page content -->
    </div>
  </div>
</body>
```

- Semua halaman menggunakan `@extends('layouts.app')`
- Konten halaman ditempatkan di `@section('content')`
- Script tambahan menggunakan `@push('scripts')`

### 1.2 Page Content Wrapper

Semua halaman harus menggunakan `<main class="p-2">` sebagai wrapper konten utama.

---

## 2. Page Types & Patterns

### 2.1 Index / List Page (Data Table)

Menggunakan `@component('components.data-page-layout', ['data' => $collection])`

**Struktur:**
```
@extends('layouts.app')
@section('content')
  @component('components.data-page-layout', ['data' => $items])
    @slot('breadcrumbs', [...])
    @slot('title', 'Page Title')
    @slot('description', 'Optional description')
    @slot('actions')
      <!-- Action buttons (right side of header) -->
    @endslot
    @slot('filters')
      <!-- Filter form fields -->
    @endslot
    @slot('exports')
      <!-- Export/print buttons -->
    @endslot
    @slot('table')
      <!-- Table with thead + tbody -->
    @endslot
  @endcomponent
@endsection
```

**Contoh:** `settings/roles/index.blade.php`

### 2.2 Create / Edit Form Page

**Tidak menggunakan** `data-page-layout`. Struktur mandiri dengan:

1. Breadcrumb (manual `<nav aria-label="breadcrumb">`) + `<hr>`
2. Title dengan back button **icon-only** (36x36px) di kiri
3. Card form (`<div class="card border-1 shadow-sm px-4 py-4">`)
4. Input fields menggunakan **`col-md-3 col-12`** (3 kolom per baris) dalam `row g-3`
5. Section headers dengan `border-bottom: 1px dashed #dee2e6`
6. Submit & Cancel buttons di kanan bawah card (`d-flex gap-2 justify-content-end mt-4`)

**Struktur:**
```
@extends('layouts.app')
@section('content')
<main class="p-2">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('...index') }}">Parent</a></li>
      <li class="breadcrumb-item active">Current Page</li>
    </ol>
  </nav>
  <hr>

  <!-- Title with Back Button (icon-only 36x36px) -->
  <div class="d-flex align-items-top gap-3 my-5">
    <a href="{{ route('...index') }}" class="btn btn-light d-flex align-items-center justify-content-center flex-shrink-0 mt-1"
       style="width: 36px; height: 36px;" title="Back to List">
      <i class="ti ti-arrow-left fs-5"></i>
    </a>
    <div>
      <h1 class="mb-1 fw-bold">Title</h1>
      <p class="text-secondary mb-4">Description</p>
    </div>
  </div>

  <!-- Card Form -->
  <div class="card border-1 shadow-sm px-4 py-4">
    <div class="col-xl-12 col-12">
      <form ...>
        @csrf
        @method('PUT') {{-- for edit --}}
        @if($errors->any())
          <div class="alert alert-danger mb-4 py-2 small">...</div>
        @endif
        <div class="row g-3">
          {{-- Section header --}}
          <div class="col-12">
            <h6 class="text-secondary fw-bold pb-2 mb-0" style="border-bottom: 1px dashed #dee2e6;">Section Name</h6>
          </div>
          {{-- Input fields: 3 columns per row --}}
          <div class="col-md-3 col-12">
            <label class="form-label">Field <span class="text-danger">*</span></label>
            <input type="text" class="form-control" ...>
            <div class="invalid-feedback">Error message</div>
          </div>
          {{-- Empty column to fill remaining space if needed --}}
          <div class="col-md-3 col-12"></div>

          {{-- Submit buttons --}}
          <div class="col-12 d-flex gap-2 justify-content-end mt-4">
            <a href="{{ route('...index') }}" class="btn btn-light border">Batal</a>
            <button type="submit" class="btn btn-primary px-4">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</main>
@endsection
```

**Aturan Input 3-Kolom:**
- Setiap input field menggunakan `col-md-3 col-12` (3 kolom per baris di layar besar)
- Untuk field lebar penuh (textarea, tag input, dll): `col-12`
- Jika dalam satu baris hanya ada 1-2 field, tambahkan `col-md-3 col-12` kosong untuk mengisi sisa
- Gunakan `row g-3` untuk grid spacing

**Contoh:** `settings/users/create.blade.php`, `settings/users/edit.blade.php`

### 2.3 Detail / Show Page

Menggunakan `data-page-layout` tanpa tabel, atau layout manual dengan breadcrumb + title + card.

### 2.4 Detail Page with Child List (Master-Detail)

Menggunakan `data-page-layout` dengan tambahan slot `backUrl` untuk tombol kembali.

**Struktur:**
```
@component('components.data-page-layout', ['data' => $childItems])
  @slot('breadcrumbs', [...])
  @slot('title', $parent->name)
  @slot('description', '...')
  @slot('backUrl', route('parent.index'))
  @slot('actions')
    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalTambah">
      <i class="ti ti-plus fs-4"></i> Tambah
    </button>
  @endslot
  @slot('table')
    <!-- Child items table -->
  @endslot
@endcomponent
```

**Contoh:** `soal-ujian/package-questions.blade.php`, `syarat-berkas/template-dokumen.blade.php`

---

## 3. Reusable Components

### 3.1 `components.data-page-layout`

Komponen utama untuk halaman yang menampilkan data tabel.

**Parameter:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `data` | Collection/Paginator | Data untuk ditampilkan (required) |

**Slots:**
| Slot | Description |
|------|-------------|
| `breadcrumbs` | Array breadcrumb: `[['label'=>'...', 'url'=>'...'], ['label'=>'...', 'active'=>true]]` |
| `title` | Judul halaman (string) |
| `description` | Deskripsi di bawah judul (string/html) |
| `backUrl` | URL untuk tombol back icon-only di kiri title (string) |
| `backLabel` | Tooltip label untuk tombol back, default "Kembali" (string) |
| `actions` | Tombol aksi di pojok kanan header (html) |
| `filters` | Field filter dalam form GET (html) |
| `exports` | Tombol export/print di card header (html) |
| `table` | Tabel data (html) |
| `pagination` | Komponen pagination / infinite scroll (html) |
| `cards` | Kartu statistik di luar main card (html) |
| `showingInfo` | Info showing kustom (html) |
| `spinner` | Flag untuk menampilkan spinner loading |
| `spinnerId` | ID spinner element (string) |
| `sentinel` | Flag untuk scroll sentinel IntersectionObserver |
| `sentinelId` | ID sentinel element (string) |

**Layout yang dihasilkan:**
```
<main class="p-2">
  [Success/Error Alerts]
  [Breadcrumbs] + <hr>
  [Title Header: backUrl? + title + description | actions]
  [Cards section]
  <div class="card border-1 shadow-sm data-page-card">
    [Card Header: filters | exports]
    [Card Header: showing info]
    [Card Body: scrollable table with sticky header]
    [Card Footer: pagination]
  </div>
</main>
```

### 3.2 `components.actions-dropdown`

Dropdown aksi untuk baris tabel.

**Usage:**
```blade
@include('components.actions-dropdown', ['items' => [
    ['url' => route('edit', $id), 'icon' => 'ti ti-edit', 'label' => 'Edit'],
    ['divider' => true],
    ['url' => route('destroy', $id), 'icon' => 'ti ti-trash', 'label' => 'Delete',
     'class' => 'text-danger', 'method' => 'DELETE', 'confirm' => 'Yakin?'],
    ['modal' => '#modalId', 'icon' => 'ti ti-calendar', 'label' => 'Schedule',
     'data' => ['id' => $id]],
    ['onclick' => "customFunc($id)", 'icon' => 'ti ti-star', 'label' => 'Custom'],
    ['html' => '<span>Custom HTML</span>'],
]])
```

**Item types:**
| Type | Key | Description |
|------|-----|-------------|
| Link | `url`, `icon`, `label` | Link biasa |
| Form action | `url`, `icon`, `label`, `method`, `confirm` | DELETE/POST/PUT dengan konfirmasi |
| Modal trigger | `modal`, `icon`, `label`, `data` | Buka modal dengan data attributes |
| Click handler | `onclick`, `icon`, `label` | Custom JavaScript |
| Divider | `divider: true` | Pemisah menu |
| Custom HTML | `html` | HTML kustom |

### 3.3 `x-sortable-header` (Component)

Sortable table header dengan AJAX support.

**Usage:**
```blade
<x-sortable-header field="column_name" label="Display Label" width="100px" align="right" />
```

**Props:**
| Prop | Default | Description |
|------|---------|-------------|
| `field` | required | Nama kolom database |
| `label` | required | Label yang ditampilkan |
| `width` | `'auto'` | Lebar kolom |
| `class` | `''` | Class tambahan |
| `align` | `'left'` | Alignment teks |

### 3.4 `components.ajax-sort-script`

Script untuk sorting tabel via AJAX tanpa reload halaman.

**Usage:**
```blade
@include('components.ajax-sort-script', ['tableBodyId' => 'my-table-body'])
```

### 3.5 `components.infinite-scroll-script`

Script untuk infinite scroll / load more data.

**Usage:**
```blade
@include('components.infinite-scroll-script', [
    'tableBodyId' => 'my-table-body',
    'spinnerId' => 'loading-spinner',
    'nextPageUrl' => $items->nextPageUrl(),
    'hasMore' => $items->hasMorePages(),
])
```

### 3.6 `components.confirm-modal` — Global Confirm Dialog

Komponen modal konfirmasi global untuk menggantikan `confirm()` bawaan browser.

**File:** `resources/views/components/confirm-modal.blade.php`

**Include (otomatis di `layouts/app.blade.php`):**
```blade
{{-- Global Confirm Modal --}}
@include('components.confirm-modal')
```

#### 3.6.1 Fungsi JavaScript Global

| Fungsi | Parameter | Deskripsi |
|--------|-----------|-----------|
| `confirmAction(event, message, options)` | event, message, options | Untuk `onclick` attribute — return `false` (mencegah default), menampilkan modal, lalu submit form / navigate jika dikonfirmasi |
| `confirmSubmit(event, message, options)` | event, message, options | Untuk `onsubmit` attribute — return `false` (mencegah submit form), menampilkan modal, lalu submit form jika dikonfirmasi |
| `confirmAsync(message, options)` | message, options | Untuk async JavaScript — return Promise (true/false). Gunakan dengan `await` di `async function` |

**Options object:**
| Property | Type | Default | Deskripsi |
|----------|------|---------|-----------|
| `confirmText` | string | `'Ya, Hapus!'` | Teks tombol konfirmasi |
| `buttonClass` | string | `'btn-danger'` | Class tombol (`btn-danger`, `btn-primary`, `btn-success`, `btn-warning`) |
| `icon` | string | `'alert-triangle'` | Nama icon Tabler (tanpa prefix `ti ti-`) |
| `iconColor` | string | `'text-warning'` | Class warna icon |
| `submessage` | string | `''` | Teks tambahan di bawah pesan utama |
| `title` | string | `'Konfirmasi'` | Judul modal |

#### 3.6.2 Aturan Penggunaan

1. **WAJIB menggunakan modal konfirmasi ini**, **DILARANG** menggunakan `confirm()` bawaan browser
2. **Untuk action hapus** (DELETE form): gunakan `confirmSubmit(event, 'Pesan')` di `onsubmit` atau `confirmAction(event, 'Pesan')` di `onclick`
3. **Untuk logout**: gunakan `confirmAction(event, 'Pesan', options)` dengan `data-form-id="headerLogoutForm"` untuk submit form hidden
4. **Untuk JavaScript async** (event listener, fetch): gunakan `await confirmAsync('Pesan', options)` di dalam `async function`
5. **Button class** disesuaikan dengan action:
   - Delete/Remove: `btn-danger` (default, icon `alert-triangle`)
   - Logout: `btn-primary` (icon `logout-2`)
   - Publish/Activate: `btn-success` (icon `check-circle`)
   - Bulk process: `btn-success` (icon `checklist`)
   - Submit exam/test: `btn-primary` (icon `check-circle`)

#### 3.6.3 Contoh Penggunaan

**Form submit (onsubmit):**
```blade
<form action="{{ route('destroy', $id) }}" method="POST"
      onsubmit="return confirmSubmit(event, 'Hapus data ini?')">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-danger">Hapus</button>
</form>
```

**Button click (onclick) — untuk form button di actions-dropdown:**
```blade
<button type="submit" class="dropdown-item text-danger"
        onclick="return confirmAction(event, 'Hapus data ini?')">
    <i class="ti ti-trash me-2"></i> Hapus
</button>
```

**Anchor link logout (dengan form hidden):**
```blade
<form action="{{ route('logout') }}" method="POST" id="logoutForm" class="d-none">@csrf</form>
<a href="#!" data-form-id="logoutForm"
   onclick="return confirmAction(event, 'Apakah Anda yakin ingin logout?', {
       confirmText: 'Ya, Logout',
       buttonClass: 'btn-primary',
       icon: 'logout-2',
       iconColor: 'text-primary',
       title: 'Konfirmasi Logout'
   })">
    <i class="ti ti-logout"></i> Logout
</a>
```

**Async JavaScript (event listener):**
```javascript
document.querySelector('.btn-delete').addEventListener('click', async function(e) {
    e.preventDefault();
    const confirmed = await confirmAsync('Apakah Anda yakin ingin menghapus data ini?');
    if (!confirmed) return;
    // proceed with delete...
});
```

**Dengan actions-dropdown component:**
```blade
@include('components.actions-dropdown', ['items' => [
    ['url' => route('destroy', $id), 'icon' => 'ti ti-trash', 'label' => 'Hapus',
     'class' => 'text-danger', 'method' => 'DELETE', 'confirm' => 'Hapus data ini?'],
]])
```

**Link dengan konfirmasi (navigasi):**
```blade
<a href="{{ route('exam.start') }}" class="btn btn-primary"
   onclick="return confirmAction(event, 'Apakah Anda yakin ingin memulai ujian?')">
    Mulai Ujian
</a>
```

---

## 4. CSS Conventions

### 4.1 Utility Classes (defined in `layouts/app.blade.php`)

| Class | Purpose |
|-------|---------|
| `.table-ead` | Standard table styling (font size, color, header styling) |
| `.table-ead.table-dotted` | Table with dotted row borders |
| `.data-page-card` | Card wrapper for data pages |
| `.data-page-table-container` | Scrollable table container with sticky header |
| `.data-page-filters` | Filter row inside card-header |
| `.data-page-pagination` | Pagination styling |
| `.sticky-header-filter` | Wrapper for breadcrumb + title (non-sticky) |
| `.sortable-header` | Sortable column header with hover effect |
| `.sort-icon` | Sort direction icon |
| `.sort-icon-active` | Active sort icon |
| `.sort-icon-muted` | Inactive sort icon |
| `.tag-input-wrapper` | Tag input wrapper |
| `.tag-input-container` | Tag input container |
| `.tag-item` | Individual tag |
| `.tag-remove` | Tag remove button |
| `.tag-dropdown` | Tag autocomplete dropdown |
| `.nav-badge` | Badge in sidebar navigation |

### 4.2 Dark Mode

Semua komponen harus mendukung dark mode dengan prefix `[data-bs-theme="dark"]`.

---

## 5. Form Patterns

### 5.1 Form Validation

- Gunakan class `needs-validation` dan `novalidate` pada form
- Setiap field required tambahkan `<span class="text-danger">*</span>`
- Setiap field harus memiliki `<div class="invalid-feedback">` untuk pesan error
- Error summary ditampilkan di atas form:
```blade
@if($errors->any())
    <div class="alert alert-danger mb-4 py-2 small">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

### 5.2 Form Layout

- Gunakan `row g-3` untuk grid form
- Gunakan section headers dengan `border-bottom: 1px dashed #dee2e6`
- Label menggunakan `form-label` + `fw-semibold`
- Input menggunakan `form-control` / `form-select`

### 5.3 Form Buttons

- Cancel: `<a href="..." class="btn btn-light border">Cancel</a>`
- Submit: `<button type="submit" class="btn btn-primary px-4">Save</button>`
- Posisi: `d-flex gap-2 justify-content-end` di bagian bawah form

---

## 6. Modal Patterns

### 6.1 Modal Structure

**Aturan:**
- **Tidak menggunakan** `style="background: linear-gradient(...)"` atau inline styling apapun
- **Tidak menggunakan** `btn-close-white` (gunakan `btn-close` default)
- **Tidak menggunakan** `text-white` pada modal title
- Struktur terdiri dari 3 bagian: **Modal Header**, **Modal Body**, **Modal Footer** (jika ada button action)
- Modal header: `border-0 pb-0` (tanpa border bawah)
- Modal footer: `border-0` (tanpa border atas)
- Modal content: `border-0 shadow`

```blade
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      {{-- Modal Header --}}
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      {{-- Modal Body --}}
      <div class="modal-body p-4">
        <form ...>
          <!-- Fields -->
        </form>
      </div>
      {{-- Modal Footer (jika ada button action) --}}
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary px-4">Simpan</button>
      </div>
    </div>
  </div>
</div>
```

### 6.2 Modal Sizes

| Class | Use Case |
|-------|----------|
| `modal-dialog` (default) | Form sederhana |
| `modal-dialog modal-lg` | Form kompleks |
| `modal-dialog modal-xl` | Tabel atau form besar |

### 6.3 Aturan Modal

1. **Dilarang** menggunakan `style="background: linear-gradient(...)"` pada modal header
2. **Dilarang** menggunakan inline styling (`style="..."`) pada komponen modal
3. **Dilarang** menggunakan `btn-close-white` — gunakan `btn-close` default
4. **Dilarang** menggunakan `text-white` pada modal title
5. **Modal Header** harus menggunakan `class="modal-header border-0 pb-0"`
6. **Modal Footer** (jika ada) harus menggunakan `class="modal-footer border-0"`
7. **Modal Content** harus menggunakan `class="modal-content border-0 shadow"`
8. Button Batal di footer: `btn btn-light border`
9. Button Submit di footer: `btn btn-primary px-4`

---

## 7. Table Patterns

### 7.1 Standard Table Structure

```blade
<table class="table table-hover align-middle mb-0 table-ead">
    <thead class="table-light">
        <tr>
            <th class="py-3" style="width:50px;">No</th>
            <th class="py-3">Column</th>
            <th class="py-3 text-center" style="width:80px;">Aksi</th>
        </tr>
    </thead>
    <tbody id="table-body">
        @if($items->isEmpty())
            <tr>
                <td colspan="3" class="text-center py-5">
                    <i class="ti ti-zoom-question text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0 text-muted">Belum ada data.</p>
                </td>
            </tr>
        @else
            @include('partials.rows')
        @endif
    </tbody>
</table>
```

### 7.2 Empty State

Semua halaman yang menampilkan data tabel harus menggunakan component `components.empty-state` untuk kondisi data kosong.

**Component:** `resources/views/components/empty-state.blade.php`

**Usage:**
```blade
{{-- Default (icon: ti-inbox, title: "Tidak Ada Data", subtitle: "Belum ada data yang tersedia saat ini.") --}}
@include('components.empty-state')

{{-- Dengan custom icon, title, dan subtitle --}}
@include('components.empty-state', [
    'icon' => 'ti-inbox',
    'title' => 'Belum ada data pendaftaran',
    'subtitle' => 'Belum ada calon mahasiswa yang melakukan submit pendaftaran.',
])

{{-- Dengan slot action (tombol) --}}
@include('components.empty-state', [
    'icon' => 'ti-file-off',
    'title' => 'Belum ada dokumen',
    'action' => '<a href="#" class="btn btn-dark btn-sm mt-2"><i class="ti ti-plus"></i> Tambah</a>',
])
```

**Component Code:**
```blade
<div class="empty-state text-center py-5">
    <div class="d-inline-flex align-items-center justify-content-center rounded-circle empty-state-icon-wrapper">
        <i class="ti {{ $icon ?? 'ti-inbox' }} text-muted empty-state-icon"></i>
    </div>
    <h6 class="mt-3 mb-1 fw-semibold empty-state-title">{{ $title ?? 'Tidak Ada Data' }}</h6>
    <p class="text-secondary mb-0 small empty-state-subtitle">{{ $subtitle ?? 'Belum ada data yang tersedia saat ini.' }}</p>
    @if(isset($action))
        <div class="mt-3">{{ $action }}</div>
    @endif
</div>
```

**CSS (di `layouts/app.blade.php`):**
```css
.empty-state-icon-wrapper {
  width: 64px;
  height: 64px;
  background-color: var(--ds-gray-200, #f4f6f8);
}
.empty-state-icon {
  font-size: 2rem;
}
.empty-state-title {
  color: var(--ds-gray-700, #454f5b);
}
.empty-state-subtitle {
  color: var(--ds-gray-500, #919eab);
}
[data-bs-theme="dark"] .empty-state-icon-wrapper {
  background-color: var(--ds-gray-700, #454f5b) !important;
}
[data-bs-theme="dark"] .empty-state-title {
  color: var(--ds-gray-300, #dfe3e8);
}
[data-bs-theme="dark"] .empty-state-subtitle {
  color: var(--ds-gray-500, #919eab);
}
```

**Aturan Empty State di Tabel:**
```blade
@forelse($items as $item)
  <tr>...</tr>
@empty
  <tr>
    <td colspan="7" class="text-center py-5">
        @include('components.empty-state')
    </td>
  </tr>
@endforelse
```

1. Gunakan `@forelse` / `@empty` untuk iterasi data
2. Di dalam `@empty`, gunakan `@include('components.empty-state')`
3. Kolom `colspan` harus sesuai jumlah kolom tabel
4. **Dilarang** menggunakan icon `ti-zoom-question`, `ti-receipt-off`, atau icon tidak standar lainnya untuk empty state
5. **Dilarang** menggunakan inline `style="font-size: 3rem;"` pada icon empty state
6. Default icon: `ti-inbox`, bisa diubah sesuai konteks via parameter `icon`
7. Default title: "Tidak Ada Data", bisa diubah via parameter `title`
8. Default subtitle: "Belum ada data yang tersedia saat ini.", bisa diubah via parameter `subtitle`

### 7.3 Actions Column

- Gunakan icon `ti-*` dengan `font-size: 3rem`
- Pesan informatif "Belum ada data."
- Tombol aksi untuk menambah data pertama (jika relevan)

### 7.3 Actions Column

- Kolom aksi menggunakan `text-center` dengan `width:80px`
- Gunakan `components.actions-dropdown` untuk multiple actions
- Untuk single action, gunakan button langsung

---

## 8. Button & Action Standards

### 8.1 Back Button

- **Hanya icon** (`ti ti-arrow-left`), tanpa teks
- Ukuran: 36x36px (`style="width: 36px; height: 36px;"`)
- Class: `btn btn-light d-flex align-items-center justify-content-center flex-shrink-0`
- Posisi: Sebelah kiri title header
- Tooltip: `title="Kembali"`
- Untuk halaman yang menggunakan `data-page-layout`, gunakan slot `@slot('backUrl')`
- Untuk halaman form manual, gunakan pattern di section 2.2

### 8.2 Primary Action Buttons

| Style | Usage |
|-------|-------|
| `btn btn-dark` | Primary action (Tambah, Buat) |
| `btn btn-primary` | Submit form |
| `btn btn-light border` | Secondary / Cancel |
| `btn btn-soft-secondary` | Batal di modal |
| `btn btn-white` | Export / Print |
| `btn btn-outline-secondary` | Navigasi sekunder |
| `btn btn-white border` | Filter row buttons (Terapkan, Reset) |

### 8.3 Filter Row Button Standards

Semua button di section filter row (di dalam `data-page-layout` slot `@slot('filters')`) menggunakan `btn-white border`:

```blade
<div class="col-md-3 col-12 d-flex gap-2">
    <button type="submit" class="btn btn-white border"><i class="ti ti-filter"></i> Terapkan</button>
    <a href="{{ route('...index') }}" class="btn btn-white border px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
</div>
```
- **Filter/Apply button:** `btn btn-white border` dengan icon `ti ti-filter`
- **Reset button:** `btn btn-white border px-3` dengan icon `ti ti-refresh` dan title `"Reset Filter"`
- **Tidak menggunakan** `btn-primary` atau `btn-subtle-primary` untuk filter row
- Posisi: `col-md-3 col-12 d-flex gap-2`

### 8.4 Button with Icon

Gunakan pattern `d-inline-flex align-items-center gap-2` untuk button dengan icon:
```blade
<button class="btn btn-dark d-inline-flex align-items-center gap-2">
    <i class="ti ti-plus fs-4"></i> Tambah
</button>
```

---

## 9. Breadcrumb Pattern

```blade
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('parent.index') }}">Parent</a></li>
        <li class="breadcrumb-item active">Current Page</li>
    </ol>
</nav>
<hr>
```

Di dalam `data-page-layout`, breadcrumb dikirim sebagai array slot:
```blade
@slot('breadcrumbs', [
    ['label' => 'Home', 'url' => route('home')],
    ['label' => 'Parent', 'url' => route('parent.index')],
    ['label' => 'Current', 'active' => true],
])
```

---

## 10. Alert / Notification Patterns

### 10.1 Success Alert
```blade
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="ti ti-circle-check fs-4 me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
```

### 10.2 Error Alert
```blade
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="ti ti-alert-triangle fs-4 me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
```

Alert session sudah otomatis ditangani oleh `data-page-layout`.

---

## 11. Icon Library

Gunakan **Tabler Icons** dengan prefix `ti ti-*`:

| Icon | Usage |
|------|-------|
| `ti ti-arrow-left` | Back button |
| `ti ti-plus` | Add / Create |
| `ti ti-edit` | Edit |
| `ti ti-trash` | Delete |
| `ti ti-device-floppy` | Save |
| `ti ti-search` | Search |
| `ti ti-filter` | Filter |
| `ti ti-refresh` | Reset |
| `ti ti-dots-vertical` | Actions dropdown |
| `ti ti-file-spreadsheet` | Export XLS |
| `ti ti-printer` | Print |
| `ti ti-table` | Table / Matrix view |
| `ti ti-database` | Data count |
| `ti ti-circle-check` | Success |
| `ti ti-alert-triangle` | Error / Warning |
| `ti ti-zoom-question` | Empty state |
| `ti ti-file-off` | Empty state (documents) |
| `ti ti-star` | Favorite / Featured |

---

## 12. Onboarding System

Sistem onboarding untuk memperkenalkan aplikasi kepada user baru menggunakan **Driver.js**.

### 12.1 Komponen

| Komponen | Path | Deskripsi |
|----------|------|-----------|
| Welcome Modal | `components.onboarding.welcome-modal` | Modal popup pertama kali login, opsi "Lanjutkan Tur" atau "Skip" |
| Checklist Widget | `components.onboarding.checklist-widget` | Floating widget di pojok kanan bawah, menampilkan progres step tutorial |
| Tour Script | `resources/js/onboarding/tour-dashboard.js` | Driver.js tour dengan step-step untuk dashboard |

### 12.2 Database

**Table:** `user_onboarding_progress`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigIncrements | Primary key |
| `user_id` | foreignId (unique) | Relasi ke users |
| `has_completed_welcome` | boolean | Sudah melihat welcome modal? |
| `completed_steps` | json/null | Array step ID yang sudah diselesaikan |
| `is_dismissed` | boolean | User menutup/dismiss onboarding? |

**Model:** `App\Models\UserOnboardingProgress`

### 12.3 Controller

**Class:** `App\Http\Controllers\OnboardingController`

| Method | Route | Description |
|--------|-------|-------------|
| `progress()` | GET `/onboarding/progress` | Mendapatkan progress onboarding user |
| `completeWelcome()` | POST `/onboarding/complete-welcome` | Menandai welcome selesai |
| `completeStep(Request)` | POST `/onboarding/complete-step` | Menandai satu step selesai |
| `dismiss()` | POST `/onboarding/dismiss` | Menutup onboarding |
| `reset()` | POST `/onboarding/reset` | Mereset onboarding (re-enable) |

### 12.4 Tour Steps (Dashboard)

Step-step yang didaftarkan di `tour-dashboard.js`:

| Step ID | Element | Deskripsi |
|---------|---------|-----------|
| `sidebar` | `#miniSidebar` | Navigasi Sidebar |
| `search` | `#content .btn-white` | Pencarian Fitur (Ctrl+K) |
| `profile` | `.ms-3.dropdown` | Menu Profile & Pengaturan |
| `sidebar-toggle` | `.sidebar-toggle` | Toggle Sidebar |
| `theme` | `.btn-ghost.btn-icon` | Mode Tampilan (Light/Dark) |
| `periode` | `.badge.bg-primary-subtle, .badge.bg-warning-subtle` | Periode Aktif |
| `notifications` | `.position-relative.btn-icon.btn-ghost` | Notifikasi |
| `content` | `#content` | Konten Dashboard |

### 12.5 Entry Points

1. **Welcome Modal** — Otomatis muncul saat pertama login (di `layouts/app.blade.php`)
2. **Profile Dropdown** — Menu "Panduan Aplikasi" di dropdown avatar (navbar)
3. **Checklist Widget** — Floating button di pojok kanan bawah (closable)

### 12.6 Library

Gunakan **Driver.js** untuk step-by-step tour:
- Package: `driver.js` (npm)
- Import: `import { driver } from 'driver.js'`
- CSS: `import 'driver.js/dist/driver.css'`

### 12.7 Integrasi di App Layout

```blade
{{-- Di layouts/app.blade.php, sebelum @stack('scripts') --}}
@include('components.onboarding.welcome-modal')
@include('components.onboarding.checklist-widget')
```

Inisialisasi otomatis memeriksa progress onboarding user via AJAX dan menampilkan welcome modal jika belum pernah melihat.

### 12.8 Completion Modal

**File:** `resources/views/components/onboarding/completion-modal.blade.php`

Modal yang muncul ketika user menyelesaikan **semua tutorial**:
- Icon selebrasi dengan animasi pulse
- Teks "Selamat! Anda telah menyelesaikan seluruh Tutorial Tour."
- Animasi **confetti** menggunakan library `canvas-confetti` (CDN)
- Tombol "Selesai" untuk menutup modal

### 12.9 Custom Step IDs

Setiap step di tutorial menggunakan `data-step` attribute. Step ID harus sesuai antara:
- Definisi tutorial di `OnboardingController::getTutorials()` — daftar step ID
- Tour JS (`tour-dashboard.js`) — `onHighlighted: () => markStep('step-id')`
- Database — `tutorials_progress[tutorial_id].completed_steps` array

---

## 13. Badge Standards

Semua badge (`<span class="badge">`) harus menggunakan **subtle variants** dengan `-subtle` background, `-emphasis` text color, dan border yang sesuai.

### 13.1 Standard Badge Classes

| Color | Class yang Digunakan |
|-------|----------------------|
| Primary | `badge text-primary-emphasis bg-primary-subtle border border-primary-subtle` |
| Secondary | `badge text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle` |
| Success | `badge text-success-emphasis bg-success-subtle border border-success-subtle` |
| Danger | `badge text-danger-emphasis bg-danger-subtle border border-danger-subtle` |
| Warning | `badge text-warning-emphasis bg-warning-subtle` (tanpa border) |
| Info | `badge text-info-emphasis bg-info-subtle border border-info-subtle` |
| Dark | `badge text-dark-emphasis bg-dark-subtle border border-dark-subtle` |
| Light | `badge text-dark-emphasis bg-light-subtle border border-light-subtle` |

### 13.2 Contoh Penggunaan

```blade
{{-- Status aktif --}}
<span class="badge text-success-emphasis bg-success-subtle border border-success-subtle px-3 py-2">Aktif</span>

{{-- Status nonaktif --}}
<span class="badge text-danger-emphasis bg-danger-subtle border border-danger-subtle px-3 py-2">Nonaktif</span>

{{-- Status warning --}}
<span class="badge text-warning-emphasis bg-warning-subtle px-3 py-2">Menunggu</span>

{{-- Badge dengan icon --}}
<span class="badge text-success-emphasis bg-success-subtle border border-success-subtle px-3 py-2">
    <i class="ti ti-circle-check me-1"></i> Selesai
</span>
```

### 13.3 Aturan

1. **Semua badge** harus menggunakan `bg-{color}-subtle` + `text-{color}-emphasis`
2. **Semua badge** (kecuali warning) harus menyertakan `border border-{color}-subtle`
3. **Warning** tidak menggunakan border (cukup `bg-warning-subtle text-warning-emphasis`)
4. **Jangan gunakan** `bg-{color}` (tanpa `-subtle`) atau `text-bg-{color}` untuk badge
5. **Jangan gunakan** `text-white` pada badge — gunakan `text-{color}-emphasis`
6. Untuk badge dinamis via PHP variable, gunakan string class lengkap:
   ```php
   $badgeBg = 'bg-success-subtle text-success-emphasis border border-success-subtle';
   ```
7. Padding standar: `px-3 py-2` untuk ukuran normal, `px-2 py-1` untuk ukuran kecil

---

## 14. Color Palette

| Token | Value | Usage |
|-------|-------|-------|
| `--ds-primary` | `#f63a4c` | Primary brand color |
| `--ds-btn-hover-bg` | `#d82939` | Button hover |
| `--ds-btn-active-bg` | `#c82635` | Button active |
| `--ds-gray-300` | (theme) | Sidebar hover/active |
| `text-secondary` | (theme) | Description text (subtitle di title header) |
| `text-danger` | `#dc3545` | Required field marker, delete |
| `text-success` | (theme) | Active status |
| `text-secondary` | (theme) | Inactive status |

---

## 15. Sidebar Component Standards

### 15.1 Sidebar Structure (`layouts/menu.blade.php`)

Sidebar menggunakan `#miniSidebar` dengan dua mode: **expanded** (lebar 250px) dan **collapsed** (lebar 60px).

**Struktur:**
```
<div id="miniSidebar">
  <div class="brand-logo">
    <a href="/home">
      <img class="brand-logo-img" width="24px" alt="" />
      <span class="fw-bold fs-4 site-logo-text">Admisi</span>
    </a>
  </div>
  <hr class="sidebar-divider">          <!-- Pemisah logo & menu -->
  <ul class="navbar-nav flex-column">
    <!-- Dynamic menu items from $sideMenus -->
  </ul>
</div>
```

### 15.2 Sidebar Divider

Garis pemisah antara brand logo dan menu list menggunakan `<hr class="sidebar-divider">`.

**CSS (di `layouts/app.blade.php`):**
```css
.sidebar-divider {
  margin: 0.35rem 1rem;
  border: 0;
  border-top: 1px solid var(--ds-border-color, #dfe3e8);
  opacity: 1;
}
html.collapsed #miniSidebar .sidebar-divider {
  margin: 0 0.5rem;
  border-top-width: 1px;
}
[data-bs-theme="dark"] .sidebar-divider {
  border-top-color: rgba(99, 115, 129, 0.25);
}
```

### 15.3 Sidebar Toggle (Maximize/Minimize)

**Trigger:** Tombol dengan class `.sidebar-toggle` di navbar (`layouts/header.blade.php`)

**Behavior:**
- Toggle class `collapsed` / `expanded` pada `<html>` element
- State disimpan di `localStorage` dengan key `sidebarExpanded`
- **Expanded:** Sidebar width 250px, menampilkan teks menu, icon, dan dropdown
- **Collapsed:** Sidebar width 60px, hanya menampilkan icon, teks menu disembunyikan

**CSS Classes:**
| Class | Sidebar Width | Content Margin | Tampilan |
|-------|---------------|----------------|----------|
| `html.expanded` | 250px | `margin-left: 15.875rem` | Teks + icon |
| `html.collapsed` | 60px | `margin-left: 3.75rem` | Icon saja |

**Content area padding (override dari tema default):**
```css
html.expanded #content {
  padding: 64px 16px !important;
}
html.collapsed #content {
  padding: 64px 16px !important;
}
```
- Padding seragam: `64px` (top/bottom) dan `16px` (left/right) — menggantikan default `80px 10px` (expanded) dan `80px 40px` (collapsed) dari tema
- Memberikan ruang konten yang lebih konsisten di kedua mode sidebar
- Diterapkan dengan `!important` untuk meng-override tema

**JavaScript (`sidebarnav.js`):**
```javascript
// Toggle sidebar state
document.querySelectorAll('.sidebar-toggle').forEach(el => {
  el.addEventListener('click', () => {
    if (localStorage.getItem('sidebarExpanded') === 'true') {
      document.documentElement.classList.add('collapsed');
      document.documentElement.classList.remove('expanded');
      localStorage.setItem('sidebarExpanded', 'false');
    } else {
      document.documentElement.classList.remove('collapsed');
      document.documentElement.classList.add('expanded');
      localStorage.setItem('sidebarExpanded', 'true');
    }
  });
});
```

### 15.4 Navbar Toggle Button

Tombol toggle di navbar menggunakan dua icon:
- **Expanded state:** `tabler-arrow-bar-left` (collapse icon) — class `collapse-mini`
- **Collapsed state:** `tabler-arrow-bar-right` (expand icon) — class `collapse-expanded`

Hanya tampil di layar `d-none d-lg-block` (desktop). Untuk mobile menggunakan offcanvas.

### 15.5 Brand Logo Padding

**Collapsed mode:**
```css
html.collapsed #miniSidebar .brand-logo {
  padding: 1.2rem !important;
}
```
- Padding: 1.2rem seragam di semua sisi — menggantikan default `.75rem 1rem` dari tema
- Memberikan ruang yang lebih proporsional untuk logo pada sidebar collapsed (60px)

### 15.6 Navbar Nav Container

**Expanded mode:**
```css
html.expanded #miniSidebar .navbar-nav {
  padding: 8px;
  height: calc(100vh - 4.5rem);
  overflow: auto;
}
```
- Padding: 8px (seragam di semua sisi) — menggantikan `padding-bottom: 30px` dari tema default
- Height: `calc(100vh - 4.5rem)` agar menu dapat di-scroll penuh
- Overflow: auto untuk scroll konten menu yang panjang

### 15.7 Nav Link Styling

| State | Background | Text Color |
|-------|-----------|------------|
| Default | Transparent | `var(--ds-gray-600)` |
| Hover | `var(--ds-gray-100)` | `var(--ds-gray-700)` |
| Active | `var(--ds-light-bg-subtle)` | `var(--ds-light-text-emphasis)` |

### 15.8 Dark Mode

```css
[data-bs-theme="dark"] #miniSidebar {
  background-color: #141a21 !important;
}
[data-bs-theme="dark"] html.expanded #miniSidebar .nav-link.active,
[data-bs-theme="dark"] html.expanded #miniSidebar .nav-link:hover {
  background-color: var(--ds-gray-300) !important;
}
[data-bs-theme="dark"] .sidebar-divider {
  border-top-color: rgba(99, 115, 129, 0.25);
}
```

### 15.9 Mobile Behavior

Pada layar < 990px:
- Sidebar disembunyikan (`display: none`)
- Navigasi menggunakan offcanvas (`#offcanvasExample`)
- Toggle button di navbar diganti dengan hamburger menu (`ti ti-menu-2`)

---

## 16. Language Switcher (Navbar)

### 16.1 Standard Language Switcher (Dropdown)

**File:** `resources/views/layouts/header.blade.php`

Language switcher menggunakan **Bootstrap dropdown**, bukan toggle pills:

```blade
<!-- Language Switcher (dropdown) -->
<li class="dropdown">
    @php $currentLocale = app()->getLocale(); @endphp
    <a class="btn btn-white border d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('Switch Language') }}">
      @if($currentLocale === 'id')
        <span class="lh-1" style="font-size: 1.1rem;">🇮🇩</span>
        <span class="d-none d-lg-inline">ID</span>
      @else
        <span class="lh-1" style="font-size: 1.1rem;">🇺🇸</span>
        <span class="d-none d-lg-inline">EN</span>
      @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end shadow">
      <li>
        <a class="dropdown-item d-flex align-items-center gap-2 {{ $currentLocale === 'id' ? 'active' : '' }}"
           href="{{ route('locale.switch', 'id') }}">
          <span class="lh-1" style="font-size: 1.1rem;">🇮🇩</span>
          <span>{{ __('Indonesia') }}</span>
        </a>
      </li>
      <li>
        <a class="dropdown-item d-flex align-items-center gap-2 {{ $currentLocale === 'en' ? 'active' : '' }}"
           href="{{ route('locale.switch', 'en') }}">
          <span class="lh-1" style="font-size: 1.1rem;">🇺🇸</span>
          <span>English (US)</span>
        </a>
      </li>
    </ul>
</li>
```

**Aturan:**
- Tombol trigger: `btn btn-white border` dengan flag + label singkat (ID/EN)
- Label singkat hanya tampil di layar `d-lg-block` (desktop)
- Dropdown menu: `dropdown-menu dropdown-menu-end shadow` (sejajar kanan)
- Setiap item dropdown: `dropdown-item d-flex align-items-center gap-2`
- Item aktif mendapat class `active` (otomatis di-highlight Bootstrap)
- Route: `route('locale.switch', 'id')` / `route('locale.switch', 'en')`
- **Dilarang** menggunakan `nav nav-pills nav-custom-pill` untuk language switcher

---

## 17. Key Rules Summary

1. **Semua halaman** harus `@extends('layouts.app')` dan konten di `@section('content')`
2. **Halaman data tabel** harus menggunakan `components.data-page-layout`
3. **Halaman form create/edit** harus menggunakan pattern manual (section 2.2)
4. **Back button** harus icon-only (`ti ti-arrow-left`) 36x36px di kiri title
5. **Actions dropdown** harus menggunakan `components.actions-dropdown`
6. **Sortable header** harus menggunakan `x-sortable-header` + `ajax-sort-script`
7. **Infinite scroll** harus menggunakan `infinite-scroll-script`
8. **Modal** harus menggunakan struktur `modal-content border-0 shadow`
9. **Tabel** harus menggunakan class `table-ead` untuk styling konsisten
10. **Dark mode** harus didukung dengan prefix `[data-bs-theme="dark"]`
11. **Icon** harus menggunakan Tabler Icons (`ti ti-*`)
12. **Form validation** harus menggunakan `needs-validation` + `novalidate`
13. **Badge** harus menggunakan subtle variants: `bg-{color}-subtle text-{color}-emphasis border border-{color}-subtle` (kecuali warning tanpa border)
14. **Onboarding** welcome modal + Driver.js tour + checklist widget untuk user baru
15. **Onboarding entry points** welcome modal otomatis, menu "Panduan Aplikasi" di profile dropdown, dan floating button di kanan bawah
