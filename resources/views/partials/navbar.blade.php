<div class="min-height-300 bg-primary position-absolute w-100"></div>
<!-- Sidebar -->

<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs fixed-start" id="sidenav-main">
    <div class="sidenav-header d-flex justify-content-between align-items-center p-3">
        <!-- Collapse Button -->

        <!-- Logo -->
        <a class="navbar-logo m-0" href="#" id="sidenavToggle">
            <img src="{{asset('assets/img/logo-app.png')}}" class="navbar-logo-img" alt="main_logo">
        </a>
    </div>

    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @if (Auth::user()->role == 'super-admin')
                <li class="nav-item">
                    <a class="nav-link">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-02 text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Pegawai</span>
                    </a>
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('master.profession') ? 'active' : '' }}"
                                href="{{ route('master.profession') }}">
                                <span class="nav-link-text">Profesi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('master.position') ? 'active' : '' }}"
                                href="{{ route('master.position') }}">
                                <span class="nav-link-text">Jabatan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('master.rank') ? 'active' : '' }}"
                                href="{{ route('master.rank') }}">
                                <span class="nav-link-text">Pangkat</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-book-open text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Kegiatan</span>
                    </a>
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('master.program') ? 'active' : '' }}"
                                href="{{ route('master.program') }}">
                                <span class="nav-link-text">Program</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('master.service') ? 'active' : '' }}"
                                href="{{ route('master.service') }}">
                                <span class="nav-link-text">Layanan</span>
                            </a>
                        </li>

                    </ul>
                </li>
            @endif

            @if (Auth::user()->role == 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('employee.index') ? 'active' : '' }}"
                        href="{{ route('employee.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-02 text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Pegawai</span>
                    </a>
                </li>
            @elseif(Auth::user()->role !== 'super-admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('activityEmployee.index') ? 'active' : '' }}"
                        href="{{ route('activityEmployee.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-book-open text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Kegiatan BOK</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->role == 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('activity.index') ? 'active' : '' }}"
                        href="{{ route('activity.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-tasks text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Kegiatan</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->role !== 'super-admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('patient.index') ? 'active' : '' }}"
                        href="{{ route('patient.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-book text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Daftar Pasien</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->role == 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('activityMonitoring.index') ? 'active' : '' }}"
                        href="{{ route('activityMonitoring.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-laptop text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Monitoring Kegiatan</span>
                    </a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-user-circle text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<main class="main-content position-relative border-radius-lg content" id="main-content">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg fixed-top px-4 shadow-none bg-primary" id="navbarBlur">
        <div class="container-fluid py-1 px-3" style="margin-left: 0px !important">
            <!-- Logo -->
            <div class="d-flex align-items-center" style="padding-top: 10px !important;">
                <div style="padding-top: 10px">
                    <a href="#" class="btn btn-outline-primary btn-sm font-weight-bold collapse-btn"
                        id="sidenavCollapseButton"
                        style="width: 50px; height: 50px; padding: 0; margin-right: 15px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-bars" style="color: white; font-size: 24px;"></i>
                        <!-- Adjust the font-size here -->
                    </a>
                </div>

                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="navbar-brand-img"
                        style="height: 50px; width: auto;">
                    <span style="color: white; line-height: 50px; margin-left: 5px;">PUSKESMAS MAKASSAR</span>
                </a>
            </div>

            <!-- Navbar Toggler for mobile view -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar"
                aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="mb-0">
                @csrf
                <button type="submit" class="dropdown-item" style="color: white">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </div>
    </nav>



    <style>
        /* BASE STYLES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .navbar-brand-img {
            height: 50px;
            width: auto;
            object-fit: contain;
            margin-right: 10px;
        }

        .navbar-logo-img {
            height: 120px;
            width: auto;
            object-fit: contain;
            margin: 0 auto;
        }

        /* MEDIA QUERIES */
        @media (max-width: 768px) {
            .navbar-brand {
                flex-direction: row;
                justify-content: space-between;
                padding: 10px;
            }

            .navbar {
                padding: 5px 10px;
            }
        }

        .collapse-btn {
            z-index: 10;
            display: block;
            margin-right: 15px;
        }
    </style>
