<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard')</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #64748b;
            --success: #22c55e;
            --info: #0ea5e9;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --light: #f8fafc;
            --sidebar-width: 260px;
            --radius-lg: 16px;
            --radius-md: 12px;
            --shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--light);
            color: var(--dark);
        }

        .main-header {
            border-bottom: 1px solid rgba(0,0,0,0.05) !important;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.8) !important;
        }

        .main-sidebar {
            background-color: var(--dark) !important;
            box-shadow: 10px 0 30px rgba(0,0,0,0.05);
        }

        .brand-link {
            border-bottom: 0 !important;
            padding: 20px 15px !important;
        }

        .nav-pills .nav-link {
            border-radius: var(--radius-md) !important;
            margin: 4px 10px;
            padding: 10px 15px;
            font-weight: 500;
            color: #94a3b8 !important;
            transition: var(--transition);
        }

        .nav-pills .nav-link:hover {
            background: rgba(255,255,255,0.05) !important;
            color: #fff !important;
            transform: translateX(5px);
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%) !important;
            color: #fff !important;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .nav-pills .nav-link i {
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .content-wrapper {
            background-color: var(--light);
            padding: 20px;
        }

        .card {
            border: 0;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            transition: var(--transition);
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.25rem 1.5rem;
        }

        .btn {
            border-radius: var(--radius-md);
            padding: 10px 20px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: 0;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        .btn-primary:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.3);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 30px;
            font-weight: 600;
        }

        .breadcrumb {
            background: transparent;
            padding: 20px 0;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    @stack('styles')

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block px-2">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Home</a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item px-3 border-right" style="border-color: rgba(0,0,0,0.05) !important;">
                    <a class="nav-link d-flex align-items-center" href="{{ route('profile.edit') }}" role="button">
                        <i class="fas fa-user-circle mr-2 text-primary"></i> 
                        <span class="font-weight-600">{{ auth()->user()->name }}</span>
                    </a>
                </li>
                <li class="nav-item pl-3">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link p-0 text-danger font-weight-bold d-flex align-items-center">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ route('admin.dashboard') }}" class="brand-link text-center"
                style="background: #1f2937; border-bottom: 3px solid #667eea;">
                <div class="logo-wrapper" style="padding: 15px 0;">
                    <div
                        style="width: 50px; height: 50px; margin: 0 auto 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                        <i class="fas fa-user-clock" style="font-size: 24px; color: white;"></i>
                    </div>
                    <h3 class="brand-text m-0"
                        style="color: white; font-weight: 700; font-size: 16px; letter-spacing: 0.5px;">
                        ATTENDANCE
                    </h3>
                </div>
            </a>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.attendance.index') }}"
                                class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>Attendance</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.qr.index') }}"
                                class="nav-link {{ request()->routeIs('admin.qr.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-qrcode"></i>
                                <p>QR Code</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('users.index') }}"
                                class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Kelola User</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('karyawan.index') }}"
                                class="nav-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-id-card"></i>
                                <p>Kelola Karyawan</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('shifts.index') }}"
                                class="nav-link {{ request()->routeIs('shifts.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clock"></i>
                                <p>Kelola Shift</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('location_settings.index') }}"
                                class="nav-link {{ request()->routeIs('location_settings.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-map-marker-alt"></i>
                                <p>Lokasi</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.integrity.index') }}"
                                class="nav-link {{ request()->routeIs('admin.integrity.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-wallet"></i>
                                <p>Dompet Integritas</p>
                            </a>
                        </li>

                        <li class="nav-header">PENGAJUAN & PENILAIAN</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.requests.index') }}"
                                class="nav-link {{ request()->routeIs('admin.requests.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-signature"></i>
                                <p>Pengajuan Karyawan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('assessment-categories.index') }}"
                                class="nav-link {{ request()->routeIs('assessment-categories.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-list-check"></i>
                                <p>Kategori Penilaian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.assessments.index') }}"
                                class="nav-link {{ request()->routeIs('admin.assessments.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-star"></i>
                                <p>Beri Penilaian</p>
                            </a>
                        </li>
                                           </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            {{-- <h1 class="m-0">@yield('page-title', 'Dashboard')</h1> --}}
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @yield('breadcrumb')
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">

                    @if (session('success'))
                        <div class="alert alert-success border-0 shadow-sm" style="border-radius: var(--radius-md); background: #dcfce7; color: #166534;">
                            <i class="fas fa-check-circle mr-2 text-success"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" style="color: #166534;">&times;</button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger border-0 shadow-sm" style="border-radius: var(--radius-md); background: #fee2e2; color: #991b1b;">
                            <i class="fas fa-exclamation-triangle mr-2 text-danger"></i>
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" style="color: #991b1b;">&times;</button>
                        </div>
                    @endif

                    @yield('content')

                </div>
            </section>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2026 Attendance System.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    @stack('scripts')

</body>

</html>
