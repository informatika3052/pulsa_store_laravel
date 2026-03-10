<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | PulsaStore Pro</title>

    <!-- Bootstrap 5 - Priority 1 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <!-- Google Fonts - Priority 2 -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons - Priority 3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <!-- Flowbite - Priority 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flowbite@2.3.0/dist/flowbite.min.css">
    
    <!-- DataTables - Priority 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --sidebar-bg: #0f172a;
            --sidebar-width: 260px;
            --topbar-height: 64px;
        }

        body { background: #f1f5f9; font-family: 'Inter', sans-serif; }

        /* === SIDEBAR === */
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1000; overflow-y: auto;
            transition: transform .3s ease;
        }
        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-brand .brand-icon {
            width: 36px; height: 36px; background: var(--primary);
            border-radius: 10px; display: flex; align-items: center;
            justify-content: center; font-size: 18px; color: white;
        }
        .sidebar-brand .brand-name {
            font-weight: 700; font-size: 1rem; color: white; margin-bottom: 0;
        }
        .sidebar-brand .brand-sub {
            font-size: .7rem; color: #64748b; text-transform: uppercase; letter-spacing: .05em;
        }
        .sidebar-section {
            padding: .75rem 1rem .25rem;
            font-size: .65rem; color: #475569;
            text-transform: uppercase; letter-spacing: .1em; font-weight: 600;
        }
        .nav-link-sidebar {
            display: flex; align-items: center; gap: .75rem;
            padding: .6rem 1.25rem;
            color: #94a3b8; text-decoration: none;
            border-radius: 8px; margin: 2px .75rem;
            transition: all .2s ease; font-size: .875rem;
        }
        .nav-link-sidebar:hover, .nav-link-sidebar.active {
            background: rgba(79,70,229,.15);
            color: #a5b4fc;
        }
        .nav-link-sidebar.active {
            background: rgba(79,70,229,.2); color: white;
        }
        .nav-link-sidebar i { font-size: 1.1rem; width: 20px; text-align: center; }

        /* === TOPBAR === */
        .topbar {
            position: fixed; top: 0; right: 0;
            left: var(--sidebar-width);
            height: var(--topbar-height);
            background: white;
            border-bottom: 1px solid #e2e8f0;
            z-index: 999;
            display: flex; align-items: center; padding: 0 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }

        /* === MAIN CONTENT === */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 1.5rem;
            min-height: calc(100vh - var(--topbar-height));
        }

        /* === CARDS === */
        .stat-card {
            background: white; border-radius: 16px;
            padding: 1.5rem; border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,.05);
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,.1);
        }
        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }

        /* === TABLES === */
        .table-card {
            background: white; border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,.05);
            overflow: hidden;
        }
        .table-card .table thead th {
            background: #f8fafc; color: #64748b;
            font-size: .75rem; text-transform: uppercase;
            letter-spacing: .05em; font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
        }
        .table-card .table tbody tr:hover { background: #f8fafc; }

        /* === BADGES === */
        .badge-role-admin { background: #ede9fe; color: #6d28d9; }
        .badge-role-kasir { background: #dbeafe; color: #1d4ed8; }
        .badge-role-gudang { background: #d1fae5; color: #065f46; }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .topbar, .main-content { left: 0; margin-left: 0; }
        }

        /* Role badge di topbar */
        .role-badge {
            padding: .25rem .75rem;
            border-radius: 999px;
            font-size: .7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        /* Flash messages */
        .flash-container {
            position: fixed; top: 80px; right: 1.5rem;
            z-index: 9999; min-width: 320px;
        }
        /* === PERBAIKAN BUTTON === */
        .btn {
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: #4f46e5 !important;
            border-color: #4f46e5 !important;
            color: white !important;
        }

        .btn-primary:hover {
            background-color: #4338ca !important;
            border-color: #4338ca !important;
        }

        .btn-outline-secondary {
            border: 1px solid #6c757d !important;
            color: #6c757d !important;
            background: transparent !important;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d !important;
            color: white !important;
        }

        /* Untuk button di dalam card/table */
        .table-card .btn {
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Pastikan tidak ada yang override */
        button:not(:disabled) {
            opacity: 1 !important;
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- FLASH MESSAGES -->
<div class="flash-container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible shadow-sm d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible shadow-sm d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible shadow-sm d-flex align-items-center" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            <div>{{ session('warning') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand d-flex align-items-center gap-3">
        <div class="brand-icon"><i class="bi bi-phone-fill"></i></div>
        <div>
            <p class="brand-name">PulsaStore Pro</p>
            <p class="brand-sub mb-0">Manajemen Inventaris</p>
        </div>
    </div>

    <nav class="py-2">
        <div class="sidebar-section">Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-link-sidebar {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>

        @if(auth()->user()->isAdmin() || auth()->user()->isKasir())
        <div class="sidebar-section">Transaksi</div>
        <a href="{{ route('sales.index') }}" class="nav-link-sidebar {{ request()->routeIs('sales.*') ? 'active' : '' }}">
            <i class="bi bi-cart-fill"></i> Penjualan
        </a>
        @endif

        @if(auth()->user()->isAdmin() || auth()->user()->isGudang())
        <a href="{{ route('purchases.index') }}" class="nav-link-sidebar {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
            <i class="bi bi-bag-fill"></i> Pembelian
        </a>
        <div class="sidebar-section">Inventaris</div>
        <a href="{{ route('products.index') }}" class="nav-link-sidebar {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam-fill"></i> Barang
        </a>
        <a href="{{ route('stock.index') }}" class="nav-link-sidebar {{ request()->routeIs('stock.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-left-right"></i> Keluar Masuk Stok
        </a>
        @endif

        @if(auth()->user()->isAdmin())
        <div class="sidebar-section">SDM & Keuangan</div>
        <a href="{{ route('employees.index') }}" class="nav-link-sidebar {{ request()->routeIs('employees.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Karyawan
        </a>
        <a href="{{ route('salary.index') }}" class="nav-link-sidebar {{ request()->routeIs('salary.*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i> Penggajian
        </a>

        <div class="sidebar-section">Laporan</div>
        <a href="{{ route('reports.sales') }}" class="nav-link-sidebar {{ request()->routeIs('reports.sales*') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i> Lap. Penjualan
        </a>
        <a href="{{ route('reports.purchases') }}" class="nav-link-sidebar {{ request()->routeIs('reports.purchases*') ? 'active' : '' }}">
            <i class="bi bi-graph-down-arrow"></i> Lap. Pembelian
        </a>
        <a href="{{ route('reports.stock') }}" class="nav-link-sidebar {{ request()->routeIs('reports.stock*') ? 'active' : '' }}">
            <i class="bi bi-clipboard-data-fill"></i> Lap. Stok
        </a>

        <div class="sidebar-section">Pengaturan</div>
        <a href="{{ route('categories.index') }}" class="nav-link-sidebar {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-tag-fill"></i> Kategori
        </a>
        <a href="{{ route('suppliers.index') }}" class="nav-link-sidebar {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i> Supplier
        </a>
        <a href="{{ route('users.index') }}" class="nav-link-sidebar {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock-fill"></i> Manajemen User
        </a>
        @endif
    </nav>
</aside>

<!-- TOPBAR -->
<header class="topbar">
    <button class="btn btn-sm btn-light me-3 d-md-none" id="sidebarToggle">
        <i class="bi bi-list fs-5"></i>
    </button>

    <h6 class="mb-0 fw-semibold text-dark">@yield('page-title', 'Dashboard')</h6>

    <div class="ms-auto d-flex align-items-center gap-3">
        <!-- Low Stock Alert -->
        @php $lowStockCount = \App\Models\Product::lowStock()->count(); @endphp
        @if($lowStockCount > 0)
        <a href="{{ route('reports.stock') }}?low_stock=1" class="btn btn-sm btn-warning position-relative">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $lowStockCount }}
            </span>
        </a>
        @endif

        <!-- User Info -->
        <div class="dropdown">
            <button class="btn btn-sm d-flex align-items-center gap-2 border-0 bg-transparent" data-bs-toggle="dropdown">
                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                    style="width:36px;height:36px;font-size:.875rem;font-weight:600;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="text-start d-none d-md-block">
                    <div style="font-size:.8rem;font-weight:600;line-height:1.2">{{ auth()->user()->name }}</div>
                    <div>
                        <span class="role-badge badge-role-{{ auth()->user()->role->name }}">
                            {{ auth()->user()->role->display_name }}
                        </span>
                    </div>
                </div>
                <i class="bi bi-chevron-down text-muted small"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li><h6 class="dropdown-header">{{ auth()->user()->email }}</h6></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

<!-- MAIN CONTENT -->
<main class="main-content">
    <!-- Breadcrumb -->
    @hasSection('breadcrumb')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            @yield('breadcrumb')
        </ol>
    </nav>
    @endif

    @yield('content')
</main>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.3.0/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<script>
    // Sidebar toggle mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // Auto dismiss flash messages
    setTimeout(() => {
        document.querySelectorAll('.flash-container .alert').forEach(el => {
            new bootstrap.Alert(el).close();
        });
    }, 4000);
</script>
@stack('scripts')
</body>
</html>
