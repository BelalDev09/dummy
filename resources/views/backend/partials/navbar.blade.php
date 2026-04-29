        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box horizontal-logo">
                            <a href="{{ asset('Backend/assets/pages/index.html') }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('Backend/assets/images/logo-sm.png') }}" alt=""
                                        height="22">
                                </span>
                                <span class="logo-lg">
                                    <img
                                        src="{{ $setting?->logo ? asset($setting->logo) : asset('Backend/assets/images/logo-dark.png') }}">
                                </span>
                            </a>

                            <a href="{{ asset('Backend/assets/pages/index.html') }}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ asset('Backend/assets/images/logo-sm.png') }}" alt=""
                                        height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('Backend/assets/images/logo-light.png') }}" alt=""
                                        height="17">
                                </span>
                            </a>
                        </div>
                        {{-- <div class="app-menu navbar-menu" id="sidebar-menu"> --}}
                        {{-- TOPNAV HAMBURGER --}}
                        <button type="button"
                            class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger material-shadow-none"
                            id="topnav-hamburger-icon">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>

                        <!-- </div> -->

                        <!-- Clock and Date/time-->

                        <div class="bookmark-wrapper d-flex align-items-center">

                            {{-- <ul class="nav navbar-nav d-xl-none">
                                <li class="nav-item">
                                    <a class="nav-link menu-toggle" href="javascript:void(0);">
                                        <i class="ficon" data-feather="menu"></i>
                                    </a>
                                </li>
                            </ul> --}}

                            <ul class="nav navbar-nav bookmark-icons">
                                <li class="nav-item d-none d-lg-block">

                                    <div class="dashboard-datetime">

                                        <div class="date-part">
                                            <i class="ri-calendar-2-line"></i>
                                            <span>{{ date('D, d M Y') }}</span>
                                        </div>

                                        <div class="divider"></div>

                                        <div class="time-part">
                                            <i class="ri-time-line"></i>
                                            <span id="timer"></span>
                                        </div>

                                    </div>

                                </li>
                            </ul>

                        </div>

                    </div>

                    <div class="d-flex align-items-center">
                        {{-- light mode and dark mode --}}

                        <div class="ms-1 header-item d-none d-sm-flex mx-4">
                            <button type="button"
                                class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle light-dark-mode">
                                <i class='bx bx-moon fs-22'></i>
                            </button>
                        </div>

                        <div class="dropdown d-md-none topbar-head-dropdown header-item">
                            <button type="button"
                                class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle"
                                id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="bx bx-search fs-22"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-search-dropdown">
                                <form class="p-3">
                                    <div class="form-group m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search ..."
                                                aria-label="Recipient's username">
                                            <button class="btn btn-primary" type="submit"><i
                                                    class="mdi mdi-magnify"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        {{-- notification --}}
                        @php
                            $user = auth()->user();

                            $notifications = $user?->notifications()->latest()->take(10)->get() ?? collect();
                            $unreadCount = $user?->notifications()->whereNull('read_at')->count() ?? 0;
                        @endphp

                        <li class="nav-item dropdown header-item">
                            <a class="nav-link position-relative" data-bs-toggle="dropdown" href="javascript:void(0)">

                                <i class="ri-notification-3-line fs-5"></i>

                                @if ($unreadCount > 0)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </a>

                            <div class="dropdown-menu dropdown-menu-end p-0 shadow-lg" style="width: 320px;">

                                <!-- Header -->
                                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Notifications</h6>

                                    @if ($unreadCount > 0)
                                        <a href="{{ route('notifications.markAllRead') }}" class="text-primary small">
                                            Mark all
                                        </a>
                                    @endif
                                </div>

                                <!-- Body -->
                                <div class="notification-scroll" style="max-height: 320px; overflow-y:auto;">

                                    @forelse ($notifications as $notification)
                                        <a href="{{ route('notifications.read', $notification->id) }}"
                                            class="d-flex p-3 border-bottom text-reset text-decoration-none
                   {{ $notification->read_at ? '' : 'bg-light' }}">

                                            <!-- Icon -->
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-xs">
                                                    <span
                                                        class="avatar-title bg-soft-primary text-primary rounded-circle fs-5">
                                                        <i class="ri-notification-2-line"></i>
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Content -->
                                            <div class="flex-grow-1">

                                                <h6 class="mb-1 fs-13">
                                                    {{ $notification->data['title'] ?? 'Notification' }}
                                                </h6>

                                                @if (!empty($notification->data['thankyou']))
                                                    <p class="mb-1 text-muted small">
                                                        {{ $notification->data['thankyou'] }}
                                                    </p>
                                                @endif

                                                <small class="text-muted">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </small>

                                            </div>

                                            @if (!$notification->read_at)
                                                <span class="badge bg-primary align-self-start">New</span>
                                            @endif

                                        </a>

                                    @empty

                                        <div class="text-center p-4 text-muted">
                                            <i class="ri-notification-off-line fs-3"></i>
                                            <p class="mb-0 mt-2">No notifications</p>
                                        </div>
                                    @endforelse

                                </div>

                                <!-- Footer -->
                                <div class="p-2 border-top text-center">
                                    <a href="#" class="text-primary small">View All</a>
                                </div>

                            </div>
                        </li>



                        <div class="dropdown ms-sm-3 header-item topbar-user" style="background-color:white">
                            @php
                                $user = Auth::user();
                            @endphp

                            <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                <span class="d-flex align-items-center">
                                    {{-- Avatar --}}
                                    @php
                                        $avatar = auth()->user()?->avatar;
                                    @endphp

                                    <img class="rounded-circle header-profile-user"
                                        src="{{ $avatar ? asset($avatar) : asset('backend/assets/images/users/avatar-1.jpg') }}"
                                        alt="User Avatar">

                                    <span class="text-start ms-xl-2">
                                        {{-- Name --}}
                                        <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
                                            {{ auth()->user()?->name ?? 'User' }}
                                        </span>

                                        {{-- Role / Designation --}}
                                        <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">
                                            {{ ucfirst($user->role ?? 'User') }}
                                        </span>
                                    </span>
                                </span>
                            </button>

                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <h6 class="dropdown-header">Welcome {{ auth()->user()?->name }}!</h6>
                                @can('profile.view')
                                    <a class="dropdown-item" href="{{ route('admin.profile') }}"><i
                                            class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                            class="align-middle">Profile Settings</span></a>
                                @endcan
                                {{-- @can('profile.edit')
                                    <a class="dropdown-item" href="{{ route('profile.update') }}"><i
                                            class="mdi mdi-account-edit text-muted fs-16 align-middle me-1"></i> <span
                                            class="align-middle">Profile Settings</span></a>
                                @endcan --}}
                                <div class="dropdown-divider"></div>
                                {{-- <a class="dropdown-item" href="#"><span
                                        class="badge bg-success-subtle text-success mt-1 float-end">New</span><i
                                        class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span
                                        class="align-middle">Settings</span></a> --}}
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">
                                        <i class="ri-logout-box-line align-middle me-1"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- sidebar --}}

        </header>
        <!-- End Header -->
