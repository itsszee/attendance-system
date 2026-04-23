<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Karyawan Dashboard')</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --dark: #1e293b;
            --light: #f8fafc;
            --radius-md: 12px;
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
            <a href="{{ route('dashboard') }}" class="brand-link text-center"
                style="background: #1f2937; border-bottom: 3px solid #6366f1;">
                <div class="logo-wrapper" style="padding: 15px 0;">
                    <div
                        style="width: 50px; height: 50px; margin: 0 auto 10px; background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);">
                        <i class="fas fa-user-check" style="font-size: 24px; color: white;"></i>
                    </div>
                    <h3 class="brand-text m-0"
                        style="color: white; font-weight: 700; font-size: 16px; letter-spacing: 0.5px;">
                        KARYAWAN
                    </h3>
                </div>
            </a>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-header mt-3">PRESENSI & INTEGRITAS</li>
                        
                        <li class="nav-item">
                            <a href="{{ route('attendance.wfo.form') }}"
                                class="nav-link {{ request()->routeIs('attendance.wfo.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-building"></i>
                                <p>Absen WFO</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('attendance.wfh.form') }}"
                                class="nav-link {{ request()->routeIs('attendance.wfh.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-laptop-house"></i>
                                <p>Absen WFH</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('wallet.index') }}"
                                class="nav-link {{ request()->routeIs('wallet.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-wallet text-success"></i>
                                <p>Dompet Integritas</p>
                            </a>
                        </li>

                        <li class="nav-header mt-3">PERSONAL</li>
                        <li class="nav-item">
                            <a href="{{ route('requests.index') }}"
                                class="nav-link {{ request()->routeIs('requests.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-signature text-warning"></i>
                                <p>Pengajuan Cuti/Izin</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.assessments.index') }}"
                                class="nav-link {{ request()->routeIs('employee.assessments.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-star-half-alt text-info"></i>
                                <p>Penilaian Saya</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.tickets.index') }}"
                                class="nav-link {{ request()->routeIs('user.tickets.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-headset text-danger"></i>
                                <p>Tiket Saya</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profile.edit') }}"
                                class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-cog text-secondary"></i>
                                <p>Profil Saya</p>
                            </a>
                        </li>
                        <li class="nav-item mt-2">
                            <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                                <i class="nav-icon fas fa-power-off text-danger"></i>
                                <p class="text-danger">Logout</p>
                            </a>
                            <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            @yield('content')
        </div>

        <footer class="main-footer text-center">
            <strong>Copyright &copy; 2026 Attendance System.</strong>
            All rights reserved.
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    @stack('scripts')
</body>

</html>