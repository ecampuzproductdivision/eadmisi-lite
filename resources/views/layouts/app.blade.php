<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta content="Codescandy" name="author">
    <title>eAdmisi | Platform Sistem Penerimaan Mahasiswa</title>
    <!-- Favicon icon-->
<link rel="icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" />

<meta name="msapplication-TileColor" content="#ffffff" />
<meta name="msapplication-TileImage" content="{{ asset('assets/images/favicon/ms-icon-144x144.png') }}" />
<meta name="theme-color" content="#ffffff" />
<!-- Color modes -->
<script src="{{ asset('assets/js/vendors/color-modes.js') }}"></script>
<script>
  if (localStorage.getItem('sidebarExpanded') === 'false') {
    document.documentElement.classList.add('collapsed');
    document.documentElement.classList.remove('expanded');
  } else {
    document.documentElement.classList.remove('collapsed');
    document.documentElement.classList.add('expanded');
  }

  function updateBrandLogo() {
    const htmlEl = document.documentElement;
    let theme = htmlEl.getAttribute('data-bs-theme');
    if (!theme || theme === 'auto') {
      theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    
    const logoImgs = document.querySelectorAll('.brand-logo-img');
    logoImgs.forEach(img => {
      if (theme === 'dark') {
        img.src = "{{ asset('assets/images/brand/logo/logo-dark.png') }}";
      } else {
        img.src = "{{ asset('assets/images/brand/logo/logo-light.png') }}";
      }
    });
  }

  const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      if (mutation.type === 'attributes' && mutation.attributeName === 'data-bs-theme') {
        updateBrandLogo();
      }
    });
  });
  observer.observe(document.documentElement, { attributes: true });

  document.addEventListener('DOMContentLoaded', updateBrandLogo);
</script>
<!-- Libs CSS -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap" />
<link rel="stylesheet" href="{{ asset('assets/libs/simplebar/dist/simplebar.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/libs/@tabler/icons-webfont/tabler-icons.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/libs/select2/css/select2.min.css') }}" />

<!-- Theme CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/theme.min.css') }}">

<style>
.btn-primary {
  --ds-btn-hover-bg: #d82939;
  --ds-btn-hover-border-color: #d82939;
  --ds-btn-active-bg: #c82635; /* slightly darker for active state */
  --ds-btn-active-border-color: #c82635;
}
.site-logo-text {
  color: #1c252e !important;
}
[data-bs-theme="dark"] .site-logo-text {
  color: #cfd1d2 !important;
}
#miniSidebar {
  z-index: 1040 !important;
}
#miniSidebar .nav-link {
  display: flex !important;
  align-items: center !important;
  gap: 10px !important;
}
#miniSidebar .nav-link .nav-icon,
#miniSidebar .nav-link .nav-icon-sub {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  width: 24px !important;
  height: 24px !important;
  margin: 0 !important;
}
#miniSidebar .nav-link .nav-icon i,
#miniSidebar .nav-link .nav-icon-sub i {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  width: 20px !important;
  height: 20px !important;
}
/* Collapsed sidebar ensures icons center */
html.collapsed #miniSidebar .nav-link .nav-icon,
html.collapsed #miniSidebar .nav-link .nav-icon-sub {
  margin: 0 auto !important;
}
/* Nav badge style */
.nav-badge {
  font-size: 0.7rem;
  padding: 2px 6px;
  border-radius: 4px;
  margin-left: auto;
}
/* Dark mode fix for the sidebar */
[data-bs-theme="dark"] .side-navbar {
  background-color: #1e293b !important;
}
[data-bs-theme="dark"] .side-navbar .navbar-header {
  background-color: #1e293b !important;
}
[data-bs-theme="dark"] .side-navbar .navbar-nav .nav-item a.active {
  background-color: #334155 !important;
}
[data-bs-theme="dark"] .side-navbar .navbar-nav .nav-item a:hover {
  background-color: #334155 !important;
}
/* Dark mode adjustments for cards and tables */
[data-bs-theme="dark"] .table {
  --bs-table-bg: transparent;
  --bs-table-hover-bg: rgba(255,255,255,0.03);
}
[data-bs-theme="dark"] .table thead th {
  background-color: #1e293b !important;
  color: #94a3b8 !important;
  border-bottom-color: #334155 !important;
}
[data-bs-theme="dark"] .table td {
  color: #cbd5e1 !important;
}
/* Dark mode: card, modal, dropdown */
[data-bs-theme="dark"] .card {
  background-color: #1e293b;
  border-color: #334155;
}
[data-bs-theme="dark"] .modal-content {
  background-color: #1e293b;
  border-color: #334155;
}
[data-bs-theme="dark"] .dropdown-menu {
  background-color: #1e293b;
  border-color: #334155;
}
[data-bs-theme="dark"] .dropdown-item {
  color: #cbd5e1;
}
[data-bs-theme="dark"] .dropdown-item:hover {
  background-color: #334155;
  color: #fff;
}
/* === Tag Input Styles === */
.tag-input-wrapper {
  position: relative;
}
.tag-input-container {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 4px;
  padding: 4px 8px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  background: #fff;
  min-height: 42px;
  cursor: text;
}
[data-bs-theme="dark"] .tag-input-container {
  background: #2b2c40;
  border-color: #3e3f5a;
}
.tag-input-wrapper.is-invalid .tag-input-container {
  border-color: #dc3545;
}
.tag-item {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  background: #fef2f2;
  color: #991b1b;
  border-radius: 4px;
  padding: 2px 8px;
  font-size: 0.8rem;
  font-weight: 500;
}
[data-bs-theme="dark"] .tag-item {
  background: #3b1c1c;
  color: #fca5a5;
}
.tag-item .tag-remove {
  cursor: pointer;
  font-weight: 700;
  color: inherit;
  opacity: 0.6;
  margin-left: 2px;
}
.tag-item .tag-remove:hover {
  opacity: 1;
}
.tag-input-field {
  border: none;
  outline: none;
  padding: 2px 4px;
  font-size: 0.875rem;
  background: transparent;
  color: inherit;
  flex: 1;
  min-width: 80px;
}
.tag-input-field::placeholder {
  color: #9ca3af;
}
.tag-dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  z-index: 1050;
  max-height: 220px;
  overflow-y: auto;
  background: #fff;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  margin-top: 2px;
}
[data-bs-theme="dark"] .tag-dropdown {
  background: #1e293b;
  border-color: #334155;
}
.tag-dropdown-item {
  padding: 8px 12px;
  font-size: 0.875rem;
  cursor: pointer;
  transition: background 0.1s;
}
.tag-dropdown-item:hover {
  background: #fef2f2;
}
[data-bs-theme="dark"] .tag-dropdown-item:hover {
  background: #3b1c1c;
}
/* === End Tag Input Styles === */

/* Sticky Header and Filter Utility - DISABLED: no longer sticky */
.sticky-header-filter {
  /* position: sticky; - removed to prevent overlapping issues */
  background-color: var(--bs-body-bg);
  padding-top: 0rem;
  padding-bottom: 0;
  margin-top: -0.5rem;
  margin-bottom: 0;
}

/* === Data Page: Table Container - NATURAL FLOW === */
/* The table expands naturally based on row count without restrictive scrolling */
.data-page-table-scroll {
  overflow: visible !important;
  max-height: none !important;
}
/* Table header is not sticky anymore (table scrolls naturally with page) */
.data-page-table-scroll .table thead {
  position: static;
}
.data-page-table-scroll .table thead th {
  position: static;
  background-color: #f8fafc;
}
[data-bs-theme="dark"] .data-page-table-scroll .table thead th {
  background-color: #1e293b;
}
/* Showing info row not sticky */
.data-page-table-scroll .showing-info-row {
  position: static;
  background-color: var(--bs-card-bg, #ffffff);
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
  margin-bottom: 0 !important;
  border-bottom: 1px solid var(--bs-border-color, #e2e8f0);
}
[data-bs-theme="dark"] .data-page-table-scroll .showing-info-row {
  background-color: var(--bs-card-bg, #1e293b);
}
/* === Consistent Table Styling === */
.table-ead tbody td {
  font-size: 0.875rem;
  font-weight: 400;
  color: #334155;
  vertical-align: middle;
}
[data-bs-theme="dark"] .table-ead tbody td {
  color: #cbd5e1;
}
.table-ead thead th {
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.03em;
  color: #64748b;
  background-color: #f8fafc;
  border-bottom: 2px solid #e2e8f0;
  vertical-align: middle;
}
[data-bs-theme="dark"] .table-ead thead th {
  color: #94a3b8;
  background-color: #1e293b;
  border-bottom-color: #334155;
}
.table-ead.table-dotted tbody tr {
  border-bottom: 1px dotted #dee2e6 !important;
}
.table-ead.table-dotted tbody tr:last-child {
  border-bottom: none !important;
}

/* Navbar glass border bottom to solid */
.navbar-glass {
  border-bottom: 1px solid var(--bs-border-color, #e2e8f0) !important;
}
/* Expanded sidebar border right to solid */
html.expanded #miniSidebar {
  border-right: 1px solid var(--bs-border-color, #e2e8f0) !important;
}

/* Pagination style improvements */
.data-page-pagination .pagination {
  margin-bottom: 0;
}
.data-page-pagination .page-link {
  padding: 0.4rem 0.75rem;
  font-size: 0.85rem;
}
</style>
  </head>

  <body>
    <!-- Vertical Sidebar -->
    <div>
      @include('layouts.menu')

      <!-- Main Content -->
      <div id="content" class="position-relative">
        @include('layouts.header')

        @yield('content')

      </div>
    </div>

    <!-- Bootstrap Icons CDN -->

    <!-- Libs JS -->
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/dist/simplebar.min.js') }}"></script>

<!-- Theme JS -->
<script src="{{ asset('assets/js/theme.min.js') }}"></script>

    <script src="{{ asset('assets/js/vendors/sidebarnav.js') }}"></script>
    <script src="{{ asset('assets/libs/jsvectormap/dist/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jsvectormap/dist/maps/world.js') }}"></script>
    <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/chart.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>

    @stack('scripts')
  </body>
</html>