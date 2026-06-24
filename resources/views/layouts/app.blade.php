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
}
#miniSidebar .nav-link .text {
  display: inline-flex !important;
  align-items: center !important;
  flex-grow: 1 !important;
}
#miniSidebar .nav-link.dropdown-toggle::after {
  margin-left: auto !important;
}
/* Breadcrumbs Custom Styling */
.breadcrumb-item + .breadcrumb-item::before {
  content: "•" !important;
  color: #cbd5e1 !important;
}
.breadcrumb-item a {
  color: #64748b;
  text-decoration: none;
}
.breadcrumb-item a:hover {
  color: #f63a4c;
}
.breadcrumb-item.active {
  color: #94a3b8;
}
/* === Custom Multi-Select Tag Input Styles === */
.tag-input-wrapper {
  position: relative;
  width: 100%;
}
.tag-input-container {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 4px;
  min-height: 38px;
  padding: 4px 8px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  background: #fff;
  cursor: text;
  transition: border-color 0.15s ease;
}
.tag-input-container:focus-within {
  border-color: #f63a4c;
  box-shadow: 0 0 0 2px rgba(246, 58, 76, 0.15);
}
[data-bs-theme="dark"] .tag-input-container {
  background: #1e293b;
  border-color: #334155;
}
[data-bs-theme="dark"] .tag-input-container:focus-within {
  border-color: #f63a4c;
  box-shadow: 0 0 0 2px rgba(246, 58, 76, 0.2);
}
.tag-input-wrapper.is-invalid .tag-input-container {
  border-color: #dc3545;
}
.tag-input-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 4px;
  align-items: center;
}
.tag-input-tag {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 2px 8px;
  font-size: 0.8125rem;
  font-weight: 500;
  background: #fef2f2;
  color: #b91c1c;
  border: 1px solid #fecaca;
  border-radius: 4px;
  white-space: nowrap;
  animation: tagIn 0.2s ease;
}
[data-bs-theme="dark"] .tag-input-tag {
  background: #3b1c1c;
  color: #fca5a5;
  border-color: #5c2a2a;
}
@keyframes tagIn {
  from { transform: scale(0.85); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}
.tag-input-remove {
  cursor: pointer;
  font-size: 0.75rem;
  line-height: 1;
  opacity: 0.6;
  transition: opacity 0.15s;
}
.tag-input-remove:hover {
  opacity: 1;
}
.tag-input-field {
  flex: 1;
  min-width: 120px;
  border: none;
  outline: none;
  padding: 2px 4px;
  font-size: 0.875rem;
  background: transparent;
  color: inherit;
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
  border-top: none;
  border-radius: 0 0 6px 6px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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

/* === Data Page: Scrollable Table Container === */
/* The card itself scrolls with the page normally */
/* Only the table area inside is scrollable with a fixed max-height */
.data-page-table-scroll {
  max-height: 400px; /* Default fallback, JS will calculate precise height */
  overflow-y: auto !important;
  overflow-x: auto !important;
}
/* When inside the scrollable container, make thead sticky */
.data-page-table-scroll .table thead {
  position: sticky;
  top: 0;
  z-index: 10;
}
.data-page-table-scroll .table thead th {
  position: sticky;
  top: 0;
  z-index: 10;
  background-color: #f8fafc;
  box-shadow: 0 2px 2px -1px rgba(0,0,0,0.1);
}
[data-bs-theme="dark"] .data-page-table-scroll .table thead th {
  background-color: #1e293b;
  box-shadow: 0 2px 2px -1px rgba(0,0,0,0.3);
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

    <!-- Data Page: Dynamic Table Scroll Height Calculator -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        function calculateTableScrollHeight() {
          const scrollContainers = document.querySelectorAll('.data-page-table-scroll');
          if (!scrollContainers.length) return;

          // Use the card's top position relative to viewport
          scrollContainers.forEach(container => {
            const card = container.closest('.data-page-card');
            if (!card) return;
            
            const cardRect = card.getBoundingClientRect();
            const cardTop = cardRect.top;
            const cardPaddingTop = parseFloat(getComputedStyle(card).paddingTop) || 0;
            const cardPaddingBottom = parseFloat(getComputedStyle(card).paddingBottom) || 0;
            
            // Find filters and pagination within this card
            const filters = card.querySelector('.data-page-filters');
            const pagination = card.querySelector('.data-page-pagination');
            
            let filtersHeight = 0;
            if (filters) {
              filtersHeight = filters.offsetHeight + (parseFloat(getComputedStyle(filters).marginBottom) || 0);
            }
            
            let paginationHeight = 0;
            if (pagination) {
              paginationHeight = pagination.offsetHeight + (parseFloat(getComputedStyle(pagination).marginTop) || 0);
            }
            
            const bottomMargin = 16; // safe margin at bottom
            const availableHeight = window.innerHeight - cardTop - cardPaddingTop - cardPaddingBottom - filtersHeight - paginationHeight - bottomMargin;
            
            container.style.maxHeight = Math.max(150, availableHeight) + 'px';
          });
        }

        calculateTableScrollHeight();
        window.addEventListener('resize', calculateTableScrollHeight);
        
        // Recalculate when sidebar toggles (collapsed/expanded)
        const sidebarObserver = new MutationObserver(function() {
          calculateTableScrollHeight();
        });
        sidebarObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
      });
    </script>
    
    @stack('scripts')
  </body>
</html>
