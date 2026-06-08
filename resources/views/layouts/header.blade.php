        <!-- navbar -->
<div class="navbar-glass navbar navbar-expand-lg px-0 px-lg-4">
  <div class="container-fluid px-lg-0">
    <div class="d-flex align-items-center gap-4">
      <!-- Collapse -->
      <div class="d-block d-lg-none">
        <a class="text-inherit" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-menu-2"
          >
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 6l16 0" />
            <path d="M4 12l16 0" />
            <path d="M4 18l16 0" />
          </svg>
        </a>
      </div>
      <div class="d-none d-lg-block">
        <a class="sidebar-toggle d-flex texttooltip p-3" href="javascript:void(0)" data-template="collapseMessage">
          <span class="collapse-mini">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-bar-left text-secondary"
            >
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M4 12l10 0" />
              <path d="M4 12l4 4" />
              <path d="M4 12l4 -4" />
              <path d="M20 4l0 16" />
            </svg>
          </span>
          <span class="collapse-expanded">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-bar-right text-secondary"
            >
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M20 12l-10 0" />
              <path d="M20 12l-4 4" />
              <path d="M20 12l-4 -4" />
              <path d="M4 4l0 16" />
            </svg>
            <div id="collapseMessage" class="d-none">
              <span class="small">Collapse</span>
            </div>
          </span>
        </a>
      </div>
      <!-- <div class="d-block d-md-none">
        <a href="/">
          <img src="{{ asset('assets/images/brand/logo/logo-light.png') }}" class="brand-logo-img" alt="" />
        </a>
      </div> -->
    </div>

    <!-- Navbar nav -->
    <ul class="list-unstyled d-flex align-items-center mb-0 gap-2">
      <!-- Pages link -->
        <button type="button" class="btn btn-white d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#searchModal">
          <span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="16"
              height="16"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="icon icon-tabler icons-tabler-outline icon-tabler-search"
            >
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <circle cx="10" cy="10" r="7" />
              <line x1="21" y1="21" x2="15" y2="15" />
            </svg>
          </span>
          <small class="text-muted">Cari fitur yang tersedia</small>
          <span class="badge bg-light text-dark border ms-1 px-1 py-0.5" style="font-size: 9px; font-weight: normal;">Ctrl+K</span>
        </button>
      <!-- Light dark mode-->
      <li>
        <div class="dropdown">
          <button class="btn btn-ghost btn-icon rounded-circle d-flex align-items-center" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="{{ __('Tema') }}">
            <i class="ti theme-icon-active lh-1 fs-5"><i class="ti theme-icon ti-sun"></i></i>
            <span class="visually-hidden bs-theme-text">{{ __('Tema') }}</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light" aria-pressed="true">
                <i class="ti theme-icon ti ti-sun"></i>
                <span class="ms-2">Light</span>
              </button>
            </li>
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                <i class="ti theme-icon ti-moon-stars"></i>
                <span class="ms-2">Dark</span>
              </button>
            </li>
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false">
                <i class="ti theme-icon ti-circle-half-2"></i>
                <span class="ms-2">Auto</span>
              </button>
            </li>
          </ul>
        </div>
      </li>

      <!-- Language Switcher (nav-custom-pill) -->
      <li>
        @php $currentLocale = app()->getLocale(); @endphp
        <ul class="nav nav-pills nav-custom-pill mb-0" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link {{ $currentLocale === 'id' ? 'active' : '' }}"
               href="{{ route('locale.switch', 'id') }}"
               title="{{ __('Indonesia') }}">
              <span class="lh-1" style="font-size: 1.1rem;">🇮🇩</span>
              <span class="ms-1 d-none d-lg-block">ID</span>
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link {{ $currentLocale === 'en' ? 'active' : '' }}"
               href="{{ route('locale.switch', 'en') }}"
               title="English (US)">
              <span class="lh-1" style="font-size: 1.1rem;">🇺🇸</span>
              <span class="ms-1 d-none d-lg-block">EN</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- Bell icon -->
      <li>
        <a class="position-relative btn-icon btn-ghost btn rounded-circle" data-bs-toggle="offcanvas" href="#offcanvasNotification" role="button" aria-controls="offcanvasNotification">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="20"
            height="20"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-bell"
          >
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
            <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
          </svg>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger mt-2 ms-n2">
            2
            <span class="visually-hidden">unread messages</span>
          </span>
        </a>
      </li>
      <!-- Dropdown -->
      <li class="ms-3 dropdown">
        <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="{{ auth()->user()->avatar_url }}" alt="" class="avatar avatar-sm rounded-circle" />
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-0">
          <div>
            <div class="d-flex gap-3 align-items-center border-dashed border-bottom px-4 py-4">
              <img src="{{ auth()->user()->avatar_url }}" alt="" class="avatar avatar-md rounded-circle" />
              <div>
                <h4 class="mb-0 fs-5">{{ auth()->user()->name }}</h4>
                <p class="mb-0 text-secondar small">{{ auth()->user()->email }}</p>
              </div>
            </div>
            <div class="p-3 d-flex flex-column gap-1">
              <a href="#!" class="dropdown-item d-flex align-items-center gap-2">
                <span
                  ><svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-activity"
                  >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 12h4l3 8l4 -16l3 8h4" />
                  </svg>
                </span>
                <span> {{ __('Activity') }}</span>
              </a>
              <a href="{{ route('account.settings') }}" class="dropdown-item d-flex align-items-center gap-2">
                <span
                  ><svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-settings"
                  >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                      d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"
                    />
                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                  </svg>
                </span>
                <span> {{ __('Account Settings') }}</span>
              </a>
            </div>
            <div class="border-dashed border-top mb-4 pt-4 px-6">
              <form action="{{ route('logout') }}" method="POST" id="headerLogoutForm" class="d-none">
                @csrf
              </form>
              <a href="#!" onclick="event.preventDefault(); document.getElementById('headerLogoutForm').submit();" class="text-secondary d-flex align-items-center gap-2">
                <span>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-login-2"
                  >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 8v-2a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-2" />
                    <path d="M3 12h13l-3 -3" />
                    <path d="M13 15l3 -3" />
                  </svg>
                </span>
                <span>{{ __('Logout') }}</span>
              </a>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </div>
</div>
<!--Offcanvas notification-->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNotification" aria-labelledby="offcanvasNotificationLabel">
  <div class="sticky-top bg-white">
    <div class="offcanvas-header gap-4">
      <div class="d-flex justify-content-between w-100">
        <h5 class="mb-0" id="offcanvasNotificationLabel">{{ __('Notifications') }}</h5>
        <div class="d-flex gap-3 align-items-center">
          <a href="#" class="link-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="{{ __('Mark all as read') }}">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="icon icon-tabler icons-tabler-outline icon-tabler-checks"
            >
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M7 12l5 5l10 -10" />
              <path d="M2 12l5 5m5 -5l5 -5" />
            </svg>
          </a>
          <a href="#" class="text-inherit">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="icon icon-tabler icons-tabler-outline icon-tabler-settings"
            >
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path
                d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"
              />
              <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
            </svg>
          </a>
        </div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="mt-2">
      <ul class="nav nav-line-bottom" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active px-4 py-2" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab" aria-controls="pills-all" aria-selected="true">
            {{ __('All') }}
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            class="nav-link px-4 py-2"
            id="pills-following-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-following"
            type="button"
            role="tab"
            aria-controls="pills-following"
            aria-selected="false"
          >
            {{ __('Following') }}
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link px-4 py-2" id="pills-archive-tab" data-bs-toggle="pill" data-bs-target="#pills-archive" type="button" role="tab" aria-controls="pills-archive" aria-selected="false">
            {{ __('Archive') }}
          </button>
        </li>
      </ul>
    </div>
  </div>

  <div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab" tabindex="0">
      <div data-simplebar="" style="height: 800px">
        <div class="list-group list-group-flush">
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex flex-column gap-1">
                <div>New message from John Doe</div>
                <small class="text-secondary"> 2 minutes ago</small>
              </div>
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex flex-column gap-1">
                <div>Your password will expire soon.</div>
                <small class="text-secondary"> 2 minutes ago</small>
              </div>
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <img src="/assets/images/avatar/avatar-1.jpg" alt="" class="avatar avatar-md rounded-circle" />
                <div class="d-flex flex-column gap-1">
                  <div>AliceÂ uploaded a new profile picture.</div>
                  <small class="text-secondary"> 1 hour ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <img src="/assets/images/avatar/avatar-2.jpg" alt="" class="avatar avatar-md rounded-circle" />
                <div class="d-flex flex-column gap-1">
                  <div>Mike sent you a friend request.</div>
                  <small class="text-secondary"> 5 minutes ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
            <div class="d-flex gap-2 align-items-center mt-4">
              <button type="button" class="btn btn-primary btn-sm">Accept</button>
              <button type="button" class="btn btn-white btn-sm">Decline</button>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <img src="/assets/images/avatar/avatar-3.jpg" alt="" class="avatar avatar-md rounded-circle" />
                <div class="d-flex flex-column gap-1">
                  <div>SophiaÂ commented on your post.</div>
                  <small class="text-secondary"> 20 minutes ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <div class="icon-shape icon-md bg-primary-subtle text-primary-emphasis rounded-circle">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-settings"
                  >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                      d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"
                    />
                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                  </svg>
                </div>
                <div class="d-flex flex-column gap-1">
                  <div>A system update has been installed.</div>
                  <small class="text-secondary"> 30 minutes ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <div class="icon-shape icon-md bg-info-subtle text-info-emphasis rounded-circle">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-week"
                  >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                    <path d="M16 3v4" />
                    <path d="M8 3v4" />
                    <path d="M4 11h16" />
                    <path d="M7 14h.013" />
                    <path d="M10.01 14h.005" />
                    <path d="M13.01 14h.005" />
                    <path d="M16.015 14h.005" />
                    <path d="M13.015 17h.005" />
                    <path d="M7.01 17h.005" />
                    <path d="M10.01 17h.005" />
                  </svg>
                </div>
                <div class="d-flex flex-column gap-1">
                  <div>Reminder: Team meeting at 3:00 PM.</div>
                  <small class="text-secondary"> 1 hour ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <div class="icon-shape icon-md bg-danger-subtle text-danger-emphasis rounded-circle">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart"
                  >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M17 17h-11v-14h-2" />
                    <path d="M6 5l14 1l-1 7h-13" />
                  </svg>
                </div>
                <div class="d-flex flex-column gap-1">
                  <div>Your order has been shipped!</div>
                  <small class="text-secondary"> 2 hours ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <img src="/assets/images/avatar/avatar-3.jpg" alt="" class="avatar avatar-md rounded-circle" />
                <div class="d-flex flex-column gap-1">
                  <div>SophiaÂ commented on your post.</div>
                  <small class="text-secondary"> 20 minutes ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <div class="icon-shape icon-md bg-primary-subtle text-primary-emphasis rounded-circle">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-settings"
                  >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                      d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"
                    />
                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                  </svg>
                </div>
                <div class="d-flex flex-column gap-1">
                  <div>A system update has been installed.</div>
                  <small class="text-secondary"> 30 minutes ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <div class="icon-shape icon-md bg-info-subtle text-info-emphasis rounded-circle">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-week"
                  >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                    <path d="M16 3v4" />
                    <path d="M8 3v4" />
                    <path d="M4 11h16" />
                    <path d="M7 14h.013" />
                    <path d="M10.01 14h.005" />
                    <path d="M13.01 14h.005" />
                    <path d="M16.015 14h.005" />
                    <path d="M13.015 17h.005" />
                    <path d="M7.01 17h.005" />
                    <path d="M10.01 17h.005" />
                  </svg>
                </div>
                <div class="d-flex flex-column gap-1">
                  <div>Reminder: Team meeting at 3:00 PM.</div>
                  <small class="text-secondary"> 1 hour ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <div class="icon-shape icon-md bg-danger-subtle text-danger-emphasis rounded-circle">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart"
                  >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M17 17h-11v-14h-2" />
                    <path d="M6 5l14 1l-1 7h-13" />
                  </svg>
                </div>
                <div class="d-flex flex-column gap-1">
                  <div>Your order has been shipped!</div>
                  <small class="text-secondary"> 2 hours ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
    <div class="tab-pane fade" id="pills-following" role="tabpanel" aria-labelledby="pills-following-tab" tabindex="0">
      <div data-simplebar="" style="height: 800px">
        <div class="list-group list-group-flush">
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <div class="icon-shape icon-md bg-info-subtle text-info-emphasis rounded-circle">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-week"
                  >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                    <path d="M16 3v4" />
                    <path d="M8 3v4" />
                    <path d="M4 11h16" />
                    <path d="M7 14h.013" />
                    <path d="M10.01 14h.005" />
                    <path d="M13.01 14h.005" />
                    <path d="M16.015 14h.005" />
                    <path d="M13.015 17h.005" />
                    <path d="M7.01 17h.005" />
                    <path d="M10.01 17h.005" />
                  </svg>
                </div>
                <div class="d-flex flex-column gap-1">
                  <div>Reminder: Team meeting at 3:00 PM.</div>
                  <small class="text-secondary"> 1 hour ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <div class="icon-shape icon-md bg-danger-subtle text-danger-emphasis rounded-circle">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart"
                  >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M17 17h-11v-14h-2" />
                    <path d="M6 5l14 1l-1 7h-13" />
                  </svg>
                </div>
                <div class="d-flex flex-column gap-1">
                  <div>Your order has been shipped!</div>
                  <small class="text-secondary"> 2 hours ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <img src="/assets/images/avatar/avatar-3.jpg" alt="" class="avatar avatar-md rounded-circle" />
                <div class="d-flex flex-column gap-1">
                  <div>SophiaÂ commented on your post.</div>
                  <small class="text-secondary"> 20 minutes ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <div class="icon-shape icon-md bg-primary-subtle text-primary-emphasis rounded-circle">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-settings"
                  >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                      d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"
                    />
                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                  </svg>
                </div>
                <div class="d-flex flex-column gap-1">
                  <div>A system update has been installed.</div>
                  <small class="text-secondary"> 30 minutes ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <div class="icon-shape icon-md bg-info-subtle text-info-emphasis rounded-circle">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-week"
                  >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                    <path d="M16 3v4" />
                    <path d="M8 3v4" />
                    <path d="M4 11h16" />
                    <path d="M7 14h.013" />
                    <path d="M10.01 14h.005" />
                    <path d="M13.01 14h.005" />
                    <path d="M16.015 14h.005" />
                    <path d="M13.015 17h.005" />
                    <path d="M7.01 17h.005" />
                    <path d="M10.01 17h.005" />
                  </svg>
                </div>
                <div class="d-flex flex-column gap-1">
                  <div>Reminder: Team meeting at 3:00 PM.</div>
                  <small class="text-secondary"> 1 hour ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <div class="icon-shape icon-md bg-danger-subtle text-danger-emphasis rounded-circle">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart"
                  >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M17 17h-11v-14h-2" />
                    <path d="M6 5l14 1l-1 7h-13" />
                  </svg>
                </div>
                <div class="d-flex flex-column gap-1">
                  <div>Your order has been shipped!</div>
                  <small class="text-secondary"> 2 hours ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
    <div class="tab-pane fade" id="pills-archive" role="tabpanel" aria-labelledby="pills-archive-tab" tabindex="0">
      <div data-simplebar="" style="height: 800px">
        <div class="list-group list-group-flush">
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex flex-column gap-1">
                <div>New message from John Doe</div>
                <small class="text-secondary"> 2 minutes ago</small>
              </div>
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex flex-column gap-1">
                <div>Your password will expire soon.</div>
                <small class="text-secondary"> 2 minutes ago</small>
              </div>
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <img src="/assets/images/avatar/avatar-1.jpg" alt="" class="avatar avatar-md rounded-circle" />
                <div class="d-flex flex-column gap-1">
                  <div>AliceÂ uploaded a new profile picture.</div>
                  <small class="text-secondary"> 1 hour ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
          </a>
          <a href="#" class="list-group-item list-group-item-action p-5 border-dashed border-bottom">
            <div class="d-flex justify-content-between">
              <div class="d-flex gap-4 align-items-center">
                <img src="/assets/images/avatar/avatar-2.jpg" alt="" class="avatar avatar-md rounded-circle" />
                <div class="d-flex flex-column gap-1">
                  <div>Mike sent you a friend request.</div>
                  <small class="text-secondary"> 5 minutes ago</small>
                </div>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle text-info">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                </svg>
              </div>
            </div>
            <div class="d-flex gap-2 align-items-center mt-4">
              <button type="button" class="btn btn-primary btn-sm">Accept</button>
              <button type="button" class="btn btn-white btn-sm">Decline</button>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="px-5 py-3 text-center bg-white position-absolute bottom-0 border-top border-dashed w-100 text-center">
    <a href="#!" class="text-inherit">View all</a>
  </div>
</div>
<!-- Modal of pages -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <input type="search" class="form-control border-0 rounded-0 ps-0 form-focus-none" id="globalSearchInput" placeholder="Cari fitur yang tersedia..." aria-label="Search" aria-describedby="search-addon" />
        <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal" aria-label="Close">Esc</button>
      </div>
      <div class="modal-body" data-simplebar="" style="height: 400px">
        <div class="mb-4" id="searchMenuList">
          @if(isset($sideMenus))
            @foreach($sideMenus as $menu)
              @if($menu->children->isNotEmpty())
                <div class="d-flex flex-column border-bottom border-dashed py-3 search-group">
                  <div class="mb-2">
                    <span class="fw-semibold text-secondary small text-uppercase">{{ __($menu->menu_name) }}</span>
                  </div>
                  <div>
                    <ul class="list-unstyled lh-lg mb-0 search-items">
                      @foreach($menu->children as $child)
                        <li class="search-item" data-name="{{ strtolower(__($menu->menu_name)) }} {{ strtolower(__($child->menu_name)) }}">
                          <a href="{{ $child->url }}" class="text-inherit d-flex align-items-center gap-2 py-1">
                            @if($child->icon)
                              <i class="ti {{ $child->icon }} text-muted fs-4"></i>
                            @else
                              <i class="ti ti-circle-filled text-muted" style="font-size: 8px;"></i>
                            @endif
                            {{ __($child->menu_name) }}
                          </a>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              @else
                <div class="d-flex flex-column border-bottom border-dashed py-3 search-group">
                  <div class="mb-2">
                    <span class="fw-semibold text-secondary small text-uppercase">{{ __('Utama') }}</span>
                  </div>
                  <div>
                    <ul class="list-unstyled lh-lg mb-0 search-items">
                      <li class="search-item" data-name="{{ strtolower(__($menu->menu_name)) }}">
                        <a href="{{ $menu->url }}" class="text-inherit d-flex align-items-center gap-2 py-1">
                          @if($menu->icon)
                            <i class="ti {{ $menu->icon }} text-muted fs-4"></i>
                          @else
                            <i class="ti ti-home text-muted fs-4"></i>
                          @endif
                          {{ __($menu->menu_name) }}
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              @endif
            @endforeach
          @else
            <div class="d-flex flex-column border-bottom border-dashed py-3 search-group">
              <div class="mb-2">
                <span class="fw-semibold text-secondary small text-uppercase">{{ __('Utama') }}</span>
              </div>
              <div>
                <ul class="list-unstyled lh-lg mb-0 search-items">
                  <li class="search-item" data-name="dashboard">
                    <a href="/home" class="text-inherit d-flex align-items-center gap-2 py-1">
                      <i class="ti ti-home text-muted fs-4"></i> Dashboard
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          @endif
        </div>
        <div id="noSearchMenuResult" class="text-center py-5 d-none">
          <i class="ti ti-search fs-1 text-muted d-block mb-2"></i>
          <span class="text-muted">{{ __('Menu tidak ditemukan.') }}</span>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const globalSearchInput = document.getElementById('globalSearchInput');
    const searchModal = document.getElementById('searchModal');

    if (globalSearchInput) {
      globalSearchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        const groups = document.querySelectorAll('#searchMenuList .search-group');
        let totalVisible = 0;

        groups.forEach(group => {
          let groupHasVisibleItems = false;
          const items = group.querySelectorAll('.search-item');

          items.forEach(item => {
            const name = item.getAttribute('data-name');
            if (name.includes(query)) {
              item.classList.remove('d-none');
              groupHasVisibleItems = true;
              totalVisible++;
            } else {
              item.classList.add('d-none');
            }
          });

          if (groupHasVisibleItems) {
            group.classList.remove('d-none');
          } else {
            group.classList.add('d-none');
          }
        });

        const noResult = document.getElementById('noSearchMenuResult');
        if (totalVisible === 0) {
          noResult.classList.remove('d-none');
        } else {
          noResult.classList.add('d-none');
        }
      });

      // Auto-focus search input when modal is shown
      if (searchModal) {
        searchModal.addEventListener('shown.bs.modal', function () {
          globalSearchInput.focus();
        });
        // Clear input when modal is closed
        searchModal.addEventListener('hidden.bs.modal', function () {
          globalSearchInput.value = '';
          const event = new Event('input');
          globalSearchInput.dispatchEvent(event);
        });
      }
    }

    // Global keyboard shortcut Ctrl+K
    document.addEventListener('keydown', function(event) {
      if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
        event.preventDefault();
        const searchBtn = document.querySelector('[data-bs-target="#searchModal"]');
        if (searchBtn) {
          searchBtn.click();
        }
      }
    });
  });
</script>

