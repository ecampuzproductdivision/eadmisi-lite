<div id="miniSidebar">
  <div class="brand-logo">
    <a class="d-none d-md-flex align-items-center gap-2 text-body text-decoration-none" href="/home">
      <img src="{{ asset('assets/images/brand/logo/logo-light.png') }}" class="brand-logo-img" width="24px" alt="" />
      <span class="fw-bold fs-4 site-logo-text">Admisi</span>
    </a>
  </div>
  @php
    // Determine user role for sidebar filtering
    $user = auth()->user();
    $isCalonMahasiswa = $user && $user->roles->contains('role_code', 'CALON_MAHASISWA');
    
    // Admin-only URL prefixes that should be hidden from calon_mahasiswa
    $adminPrefixes = ['settings/', 'pendaftaran'];
  @endphp
  <ul class="navbar-nav flex-column">
    @if(isset($sideMenus))
      @foreach($sideMenus as $menu)
        @php
          // Check if this menu is admin-only
          $isAdminOnly = false;
          if ($isCalonMahasiswa) {
            $menuUrl = trim($menu->url ?? '', '/');
            foreach ($adminPrefixes as $prefix) {
              if (str_starts_with($menuUrl, $prefix)) {
                $isAdminOnly = true;
                break;
              }
            }
            // Also check children
            if (!$isAdminOnly && $menu->children->isNotEmpty()) {
              foreach ($menu->children as $child) {
                $childUrl = trim($child->url ?? '', '/');
                foreach ($adminPrefixes as $prefix) {
                  if (str_starts_with($childUrl, $prefix)) {
                    $isAdminOnly = true;
                    break 2;
                  }
                }
              }
            }
          }
          if ($isAdminOnly) continue;
        @endphp

        @if($menu->children->isNotEmpty())
          <!-- Dynamic Parent Menu with Children -->
          @php
            $childUrls = $menu->children->pluck('url')->map(fn($url) => trim($url, '/'))->toArray();
            $anyActive = false;
            foreach ($childUrls as $curl) {
              if (request()->is($curl) || request()->is($curl . '/*')) {
                $anyActive = true;
                break;
              }
            }
          @endphp
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ $anyActive ? 'show' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="{{ $anyActive ? 'true' : 'false' }}">
              <span class="nav-icon">
                @if(str_contains($menu->icon ?? '', 'ti-'))
                  <i class="{{ str_starts_with($menu->icon ?? '', 'ti ') ? $menu->icon : 'ti ' . $menu->icon }} fs-3"></i>
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <rect x="4" y="4" width="6" height="6" rx="1" />
                    <rect x="14" y="4" width="6" height="6" rx="1" />
                    <rect x="4" y="14" width="6" height="6" rx="1" />
                    <rect x="14" y="14" width="6" height="6" rx="1" />
                  </svg>
                @endif
              </span>
              <span class="text">{{ __($menu->menu_name) }}</span>
            </a>
            <ul class="dropdown-menu flex-column {{ $anyActive ? 'show' : '' }}">
              @foreach($menu->children as $child)
                @php
                  // Filter child admin menus for calon_mahasiswa
                  if ($isCalonMahasiswa) {
                    $childUrl = trim($child->url ?? '', '/');
                    $isChildAdmin = false;
                    foreach ($adminPrefixes as $prefix) {
                      if (str_starts_with($childUrl, $prefix)) {
                        $isChildAdmin = true;
                        break;
                      }
                    }
                    if ($isChildAdmin) continue;
                  }
                  $trimmedChildUrl = trim($child->url, '/');
                  $childActive = request()->is($trimmedChildUrl) || request()->is($trimmedChildUrl . '/*');
                @endphp
                <li class="nav-item">
                  <a class="nav-link {{ $childActive ? 'active' : '' }}" href="{{ $child->url }}">
                    <span class="nav-icon-sub me-2">
                      @if($child->icon)
                        <i class="{{ str_starts_with($child->icon ?? '', 'ti ') ? $child->icon : 'ti ' . $child->icon }} fs-5"></i>
                      @else
                        <i class="ti ti-circle-filled fs-5"></i>
                      @endif
                    </span>
                    {{ __($child->menu_name) }}
                  </a>
                </li>
              @endforeach
            </ul>
          </li>
        @else
          <!-- Dynamic Single Menu Item -->
          @php
            $trimmedUrl = trim($menu->url, '/');
            $isActive = request()->is($trimmedUrl) || request()->is($trimmedUrl . '/*');
          @endphp
          <li class="nav-item">
            <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ $menu->url }}">
              <span class="nav-icon">
                @if(str_contains($menu->icon ?? '', 'ti-'))
                  <i class="{{ str_starts_with($menu->icon ?? '', 'ti ') ? $menu->icon : 'ti ' . $menu->icon }} fs-3"></i>
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-home">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                  </svg>
                @endif
              </span>
              <span class="text">{{ __($menu->menu_name) }}</span>
            </a>
          </li>
        @endif
      @endforeach
    @else
      <!-- Fallback when not logged in or dynamic menus not loaded -->
      <li class="nav-item">
        <a class="nav-link active" href="/home">
          <span class="nav-icon"><i class="ti ti-home fs-3"></i></span>
          <span class="text">{{ __('Home') }}</span>
        </a>
      </li>
    @endif
  </ul>
</div>
