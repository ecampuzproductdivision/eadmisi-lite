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
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" />
<link rel="stylesheet" href="{{ asset('assets/libs/simplebar/dist/simplebar.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/libs/@tabler/icons-webfont/tabler-icons.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/libs/select2/css/select2.min.css') }}" />

<!-- Theme CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/theme.min.css') }}">

<style>
body {
  font-family: 'Inter', sans-serif;
}
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
  background-color: #f8fafc !important;
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
/* Collapsed sidebar brand logo - override theme.min.css padding */
html.collapsed #miniSidebar .brand-logo {
  padding: 1.2rem !important;
}
/* Sidebar Divider - separates brand logo from menu items */
.sidebar-divider {
  margin: 0.35rem 1rem;
  border: 0;
  border-top: 1px solid var(--ds-border-color, #dfe3e8);
  opacity: 1;
}
/* Collapsed sidebar: thinner divider with less margin */
html.collapsed #miniSidebar .sidebar-divider {
  margin: 0 0.5rem;
  border-top-width: 1px;
}
/* Dark mode sidebar divider */
[data-bs-theme="dark"] .sidebar-divider {
  border-top-color: rgba(99, 115, 129, 0.25);
}

/* Sidebar Section Header */
.nav-section-header .nav-link {
  padding: 0.5rem 0.75rem 0.25rem 0.75rem !important;
  font-size: 0.65rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: #64748b !important;
  cursor: default;
  pointer-events: none;
}
[data-bs-theme="dark"] .nav-section-header .nav-link {
  color: #94a3b8 !important;
}
html.collapsed #miniSidebar .nav-section-header {
  display: none;
}

/* Collapsed sidebar ensures icons center */
html.collapsed #miniSidebar .nav-link .nav-icon,
html.collapsed #miniSidebar .nav-link .nav-icon-sub {
  margin: 0 auto !important;
}
/* Expanded sidebar navbar-nav - override theme.min.css padding-bottom:30px */
html.expanded #miniSidebar .navbar-nav {
  padding: 8px;
  height: calc(100vh - 4.5rem);
  overflow: auto;
}
/* Expanded sidebar nav-link text full width */
html.expanded #miniSidebar .nav-link .text {
  width: 100% !important;
}
/* Sidebar nav link active & hover states (expanded) */
html.expanded #miniSidebar .nav-link.active,
html.expanded #miniSidebar .nav-link:hover {
  background-color: var(--ds-gray-300) !important;
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
[data-bs-theme="dark"] #miniSidebar {
  background-color: #141a21 !important;
}
[data-bs-theme="dark"] html.expanded #miniSidebar .nav-link.active,
[data-bs-theme="dark"] html.expanded #miniSidebar .nav-link:hover {
  background-color: var(--ds-gray-300) !important;
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

/* === Data Page Card: Header + Body Layout === */
/* Filters row inside card-header */
.data-page-filters {
  padding-top: 0.25rem;
  padding-bottom: 0.25rem;
  margin-bottom: 0 !important;
}
/* Table inside data-page-card - ensure full width */
.data-page-card table {
  width: 100%;
}
/* Card Body as scrollable table container with sticky header */
.data-page-table-container {
  overflow: auto;
  max-height: 60vh;
}
.data-page-table-container table thead {
  position: sticky;
  top: 0;
  z-index: 20;
}
.data-page-table-container table thead th {
  position: sticky;
  top: 0;
  z-index: 19;
  background-color: #f8fafc;
}
[data-bs-theme="dark"] .data-page-table-container table thead th {
  background-color: #1e293b;
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
  height: 100%;
}

/* Pagination style improvements */
.data-page-pagination .pagination {
  margin-bottom: 0;
}
.data-page-pagination .page-link {
  padding: 0.4rem 0.75rem;
  font-size: 0.85rem;
}

/* Sortable Header Styles */
.sortable-header {
  color: inherit;
  white-space: nowrap;
  user-select: none;
}
.sortable-header:hover {
  color: var(--ds-primary, #f63a4c);
}
.sortable-header .sort-icon {
  font-size: 0.7rem;
  transition: opacity 0.2s;
  opacity: 0.4;
}
.sortable-header:hover .sort-icon {
  opacity: 1;
}
.sortable-header .sort-icon-active {
  opacity: 1;
  color: var(--ds-primary, #f63a4c);
}
.sortable-header .sort-icon-muted {
  opacity: 0.3;
}
[data-bs-theme="dark"] .sortable-header:hover {
  color: var(--ds-primary-text-emphasis, #66caa9);
}
[data-bs-theme="dark"] .sortable-header .sort-icon-active {
  color: var(--ds-primary-text-emphasis, #66caa9);
}

/* Empty State Component Styles */
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

/* Input value color - darker than placeholder for better contrast */
.form-control,
.form-select,
textarea.form-control {
  color: #334155;
}
.form-control:focus,
.form-select:focus,
textarea.form-control:focus {
  color: #334155;
}
.form-control::placeholder,
.form-select::placeholder,
textarea.form-control::placeholder {
  color: #94a3b8;
}
[data-bs-theme="dark"] .form-control,
[data-bs-theme="dark"] .form-select,
[data-bs-theme="dark"] textarea.form-control {
  color: #e2e8f0;
}
[data-bs-theme="dark"] .form-control:focus,
[data-bs-theme="dark"] .form-select:focus,
[data-bs-theme="dark"] textarea.form-control:focus {
  color: #e2e8f0;
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
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
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
    <script src="{{ asset('assets/js/vendors/location-search.js') }}"></script>

    {{-- Driver.js --}}
    <link rel="stylesheet" href="{{ asset('assets/js/onboarding/driver.css') }}" />
    <script src="{{ asset('assets/js/onboarding/driver.js.iife.js') }}"></script>

    {{-- Canvas Confetti for completion celebration --}}
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1"></script>

    {{-- Onboarding Tour Script --}}
    <script src="{{ asset('assets/js/onboarding/tour-dashboard.js') }}"></script>

    {{-- Onboarding Components --}}
    @include('components.onboarding.welcome-modal')
    @include('components.onboarding.checklist-widget')
    @include('components.onboarding.completion-modal')

    {{-- Global Confirm Modal --}}
    @include('components.confirm-modal')

    {{-- Onboarding Initialization Script --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if onboarding should show
        const welcomeModal = document.getElementById('welcomeOnboardingModal');
        if (!welcomeModal) return;

        // Check if user has incomplete profile (blocking modal active)
        @auth
        @if(!auth()->user()->isProfileComplete())
        return;
        @endif
        @endauth

        // Check if we have onboarding progress data
        fetch('{{ route("onboarding.progress") }}')
            .then(r => r.json())
            .then(data => {
                // Show welcome modal if user hasn't completed welcome
                if (!data.has_completed_welcome && !data.is_dismissed) {
                    const modal = new bootstrap.Modal(welcomeModal, {
                        backdrop: 'static',
                        keyboard: false
                    });
                    modal.show();
                }

                // Show checklist widget if onboarding has been started but not fully dismissed
                const widget = document.getElementById('onboardingChecklistWidget');
                const hasAnyProgress = data.tutorials_progress && Object.values(data.tutorials_progress).some(t => t.completed_steps && t.completed_steps.length > 0);
                if (widget && (data.has_completed_welcome || hasAnyProgress) && !data.is_dismissed) {
                    widget.style.display = 'block';
                }
            })
            .catch(() => {});

        // Handle onboarding menu entry click in profile dropdown
        const onboardingMenuEntry = document.getElementById('onboardingMenuEntry');
        if (onboardingMenuEntry) {
            onboardingMenuEntry.addEventListener('click', function(e) {
                e.preventDefault();
                // Close the dropdown
                const dropdown = this.closest('.dropdown-menu');
                if (dropdown) {
                    const toggle = dropdown.parentElement.querySelector('[data-bs-toggle="dropdown"]');
                    if (toggle) {
                        const instance = bootstrap.Dropdown.getInstance(toggle);
                        if (instance) instance.hide();
                    }
                }
                // Show checklist widget
                const widget = document.getElementById('onboardingChecklistWidget');
                if (widget) {
                    widget.style.display = 'block';
                    const panel = document.getElementById('onboardingChecklistPanel');
                    if (panel) panel.style.display = 'block';
                }
                // Start tour if not already completed
                if (typeof startDashboardTour === 'function') {
                    setTimeout(() => startDashboardTour(), 300);
                }
            });
        }

        // Handle completion modal close — clean up
        const completionModal = document.getElementById('tutorialCompletionModal');
        if (completionModal) {
            completionModal.addEventListener('hidden.bs.modal', function() {
                // Re-enable page interaction
                document.body.classList.remove('modal-open');
            });
        }
    });
    </script>

    @include('components.modal-complete-profile')

    @stack('scripts')
<script>
// Handle dropdown overflow inside scrollable containers
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.dropdown-actions-btn');
    if (btn) {
        var container = btn.closest('.data-page-table-container');
        if (container) {
            container.style.overflow = 'visible';
            var dropdownEl = btn.nextElementSibling;
            if (dropdownEl) {
                var onHidden = function() {
                    container.style.overflow = '';
                    dropdownEl.removeEventListener('hidden.bs.dropdown', onHidden);
                };
                dropdownEl.addEventListener('hidden.bs.dropdown', onHidden);
            }
        }
    }
});

// Sidebar toggle function - called from navbar toggle button
function toggleSidebar(e) {
    if (e) e.stopImmediatePropagation();
    var expanded = localStorage.getItem('sidebarExpanded');
    if (expanded === 'false') {
        document.documentElement.classList.remove('collapsed');
        document.documentElement.classList.add('expanded');
        localStorage.setItem('sidebarExpanded', 'true');
    } else {
        document.documentElement.classList.remove('expanded');
        document.documentElement.classList.add('collapsed');
        localStorage.setItem('sidebarExpanded', 'false');
    }
}
</script>
  </body>
</html>