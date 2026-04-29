{{-- SIDEBAR --}}
<div class="app-menu navbar-menu border-end-dashed">

    {{-- LOGO --}}
    <div class="navbar-brand-box">


        <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">


            <span class="logo-sm">
                <img src="{{ asset($setting->admin_logo) }}" style="width:40px; height:40px; object-fit:contain;"
                    onerror="this.src='{{ asset('Backend/assets/images/no-image.png') }}'">
            </span>

            <span class="logo-lg">
                <img src="{{ asset($setting->admin_logo) }}" style="width:100%; max-height:50px; object-fit:contain;"
                    onerror="this.src='{{ asset('Backend/assets/images/no-image.png') }}'">
            </span>

        </a>


        <a href="{{ route('admin.dashboard') }}" class="logo logo-light">

            <span class="logo-sm">
                <img src="{{ asset($setting->admin_logo) }}" style="width:40px; height:40px; object-fit:contain;"
                    onerror="this.src='{{ asset('Backend/assets/images/no-image.png') }}'">
            </span>

            <span class="logo-lg">
                <img src="{{ asset($setting->admin_logo) }}" style="width:100%; max-height:50px; object-fit:contain;"
                    onerror="this.src='{{ asset('Backend/assets/images/no-image.png') }}'">
            </span>

        </a>

    </div>

    {{-- MENU --}}
    <div id="scrollbar" data-simplebar class="h-100">
        <ul class="navbar-nav" id="navbar-nav">

            {{-- DASHBOARD --}}
            <li class="nav-item">
                <a class="nav-link menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="ri-dashboard-2-line"></i>
                    <span>Home</span>
                </a>
            </li>

            {{-- CONTENT --}}
            <li class="menu-title"><span>Content</span></li>

            {{-- USER MANAGEMENT --}}
            @php
                $userManagementOpen = request()->routeIs('admin.user.*', 'admin.roles.*', 'admin.permissions.*');
            @endphp
            <li class="nav-item">
                <a class="nav-link menu-link {{ $userManagementOpen ? 'active' : 'collapsed' }}" href="#sidebarUser"
                    data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ $userManagementOpen ? 'true' : 'false' }}" aria-controls="sidebarUser">
                    <i class="ri-team-line"></i>
                    <span>User Management</span>
                </a>
                <div class="collapse menu-dropdown {{ $userManagementOpen ? 'show' : '' }}" id="sidebarUser">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.user.list') }}"
                                class="nav-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                                Users List
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.list') }}"
                                class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                Roles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.permissions.list') }}"
                                class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                                Permissions
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- CATEGORY --}}
            <li class="nav-item">
                <a class="nav-link menu-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                    href="{{ route('admin.categories.index') }}">
                    <i class="ri-price-tag-3-line"></i>
                    <span>Category</span>
                </a>
            </li>

            {{-- SUB CATEGORY --}}
            <li class="nav-item">
                <a class="nav-link menu-link {{ request()->routeIs('admin.sub-categories.*') ? 'active' : '' }}"
                    href="{{ route('admin.sub-categories.index') }}">
                    <i class="ri-price-tag-2-line"></i>
                    <span>Sub Category</span>
                </a>
            </li>

            {{-- BRANDS --}}
            <li class="nav-item">
                <a class="nav-link menu-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}"
                    href="{{ route('admin.brands.index') }}">
                    <i class="ri-award-line"></i>
                    <span>Brands</span>
                </a>
            </li>

            {{-- PRODUCTS --}}
            <li class="nav-item">
                <a class="nav-link menu-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                    href="{{ route('admin.products.index') }}">
                    <i class="ri-shopping-bag-3-line"></i>
                    <span>Products</span>
                </a>
            </li>

            {{-- CMS --}}
            <li class="menu-title"><span>CMS Section</span></li>

            {{-- HOME PAGE --}}
            @php
                $homePageOpen = request()->routeIs('admin.cms.home_page.*');
            @endphp
            <li class="nav-item">
                <a class="nav-link menu-link {{ $homePageOpen ? 'active' : 'collapsed' }}" href="#cmsHomepage"
                    data-bs-toggle="collapse" role="button" aria-expanded="{{ $homePageOpen ? 'true' : 'false' }}"
                    aria-controls="cmsHomepage">
                    <i class="ri-pages-line"></i>
                    <span>Home Page</span>
                </a>
                <div class="collapse menu-dropdown {{ $homePageOpen ? 'show' : '' }}" id="cmsHomepage">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.cms.home_page.top_section') }}"
                                class="nav-link {{ request()->routeIs('admin.cms.home_page.top_section') ? 'active' : '' }}">
                                Top Section
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.cms.home_page.category_section') }}"
                                class="nav-link {{ request()->routeIs('admin.cms.home_page.category_section') ? 'active' : '' }}">
                                Category Section
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.cms.home_page.men_collection_section') }}"
                                class="nav-link {{ request()->routeIs('admin.cms.home_page.men_collection_section') ? 'active' : '' }}">
                                Men Collection Section
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.cms.home_page.women_collection_section') }}"
                                class="nav-link {{ request()->routeIs('admin.cms.home_page.women_collection_section') ? 'active' : '' }}">
                                Women Collection Section
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.cms.home_page.watches_section') }}"
                                class="nav-link {{ request()->routeIs('admin.cms.home_page.watches_section') ? 'active' : '' }}">
                                Watches Section
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.cms.home_page.high_tech_section') }}"
                                class="nav-link {{ request()->routeIs('admin.cms.home_page.high_tech_section') ? 'active' : '' }}">
                                High Tech Section
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Cart Manage --}}
            <li class="nav-item">
                <a class="nav-link menu-link {{ request()->routeIs('admin.carts.index') ? 'active' : '' }}"
                    href="{{ route('admin.carts.index') }}">
                    <i class="ri-shopping-cart-2-line"></i>
                    <span>Cart Manage</span>
                </a>
            </li>

            {{-- Orders --}}
            <li class="nav-item">
                <a class="nav-link menu-link {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}"
                    href="{{ route('admin.orders.index') }}">
                    <i class="ri-shopping-bag-fill"></i>
                    <span>Order Pages</span>
                </a>
            </li>

            {{-- Subscribers --}}
            <li class="nav-item">
                <a class="nav-link menu-link {{ request()->routeIs('admin.newsletters.index') ? 'active' : '' }}"
                    href="{{ route('admin.newsletters.index') }}">
                    <i class="ri-user-follow-line"></i>
                    <span>Subscribers Pages</span>
                </a>
            </li>

            {{-- DYNAMIC PAGES --}}
            @php
                $dynamicOpen = request()->routeIs('admin.dynamicpages.*', 'admin.dynamic.cms.*');
            @endphp
            <li class="nav-item">
                <a class="nav-link menu-link {{ $dynamicOpen ? 'active' : 'collapsed' }}" href="#sidebarDynamic"
                    data-bs-toggle="collapse" role="button" aria-expanded="{{ $dynamicOpen ? 'true' : 'false' }}"
                    aria-controls="sidebarDynamic">
                    <i class="ri-file-text-line"></i>
                    <span>Dynamic Pages</span>
                </a>
                <div class="collapse menu-dropdown {{ $dynamicOpen ? 'show' : '' }}" id="sidebarDynamic">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.dynamicpages.index') }}"
                                class="nav-link {{ request()->routeIs('admin.dynamicpages.*') ? 'active' : '' }}">
                                Pages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.dynamic.cms.index') }}"
                                class="nav-link {{ request()->routeIs('admin.dynamic.cms.*') ? 'active' : '' }}">
                                CMS Dynamic Pages
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- SOCIAL SETTINGS --}}
            <li class="nav-item">
                <a class="nav-link menu-link {{ request()->routeIs('admin.social-settings.index') ? 'active' : '' }}"
                    href="{{ route('admin.social-settings.index') }}">
                    <i class="ri-share-line"></i>
                    <span>Social Settings</span>
                </a>
            </li>

            {{-- SETTINGS --}}

            @php

                $isSettingsOpen = request()->routeIs(
                    'admin.profile',
                    'admin.system.setting',
                    'admin.system.setting.*',
                    'admin.setting',
                    'admin.setting.mail',
                    'admin.setting.mail.*',
                    'admin.faq.*',
                );

                $isSystemSettingActive = request()->routeIs('admin.system.setting', 'admin.system.setting.*');
                $isAdminSettingActive = request()->routeIs('admin.setting');

                $isMailSettingActive = request()->routeIs('admin.setting.mail', 'admin.setting.mail.*');
                $isFaqActive = request()->routeIs('admin.faq.*');
                $isProfileActive = request()->routeIs('admin.profile');
            @endphp

            <li class="nav-item">
                <a class="nav-link menu-link {{ $isSettingsOpen ? 'active' : 'collapsed' }}" href="#sidebarSettings"
                    data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ $isSettingsOpen ? 'true' : 'false' }}" aria-controls="sidebarSettings">
                    <i class="ri-settings-4-line"></i>
                    <span>Settings</span>
                </a>

                <div class="collapse menu-dropdown {{ $isSettingsOpen ? 'show' : '' }}" id="sidebarSettings">
                    <ul class="nav nav-sm flex-column">

                        <li class="nav-item">
                            <a href="{{ route('admin.profile') }}"
                                class="nav-link {{ $isProfileActive ? 'active' : '' }}">
                                Profile Settings
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.system.setting') }}"
                                class="nav-link {{ $isSystemSettingActive ? 'active' : '' }}">
                                System Setting
                            </a>
                        </li>

                        <li class="nav-item">

                            <a href="{{ route('admin.setting') }}"
                                class="nav-link {{ $isAdminSettingActive ? 'active' : '' }}">
                                Admin Setting
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.setting.mail') }}"
                                class="nav-link {{ $isMailSettingActive ? 'active' : '' }}">
                                Mail Setting
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.faq.index') }}"
                                class="nav-link {{ $isFaqActive ? 'active' : '' }}">
                                FAQ Setting
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

        </ul>
    </div>

    <div class="sidebar-background"></div>
</div>
