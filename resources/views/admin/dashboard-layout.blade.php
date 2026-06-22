<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - BengkelConnect')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #fafafa; color: #1e293b; }
.container { display:grid; grid-template-columns:250px 1fr; gap:20px; max-width: 1200px; margin: 0 auto; padding: 30px 20px; align-items:start; }

        /* Mekanik tanpa sidebar: biar tidak memanjang */
        body[data-role="mekanik"] .container { grid-template-columns: 1fr; padding-top: 18px; }
        body[data-role="mekanik"] .top-actions { margin-bottom: 12px; }
        body[data-role="mekanik"] h1 { margin-bottom: 6px; }
        .sidebar { background:#fff; border:1px solid rgba(0,0,0,0.06); border-radius:20px; padding:20px; box-shadow:0 12px 30px rgba(15,23,42,.06); }
        .sidebar h2 { font-size:16px; margin-bottom:16px; color:#111827; }
        .sidebar .nav-link { display:flex; align-items:center; gap:10px; width:100%; padding:12px 14px; margin-bottom:10px; border-radius:14px; text-decoration:none; color:#334155; font-weight:600; background:#f8fafc; transition:0.2s; }
        .sidebar .nav-link i { font-size:18px; }
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover { background:#dc2626; color:#ffffff; }
        .main-content { display:flex; flex-direction:column; gap:20px; }
        h1 { font-size: 28px; font-weight: 700; margin-bottom: 10px; }
        .top-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .logout a { color: #dc2626; text-decoration: none; font-weight: 600; }
        .card { background: #fff; border: 1px solid rgba(0,0,0,0.06); border-radius: 16px; overflow: hidden; }
        @media (max-width: 960px) {
            .container { grid-template-columns: 1fr; }
            .sidebar { order: 2; }
        }
        @yield('styles')
    </style>
</head>
<body>
    <div class="container" style="{{ session('role') === 'mekanik' ? 'grid-template-columns: 1fr; padding-top:18px;' : '' }}">
        {{-- Untuk mekanik: hilangkan sidebar admin --}}
        @if(session('role') !== 'mekanik')
            <aside class="sidebar">
                <h2>Admin Menu</h2>

                <a href="/admin/dashboard" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="/admin/teknisi" class="nav-link {{ request()->is('admin/teknisi') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Kelola Teknisi
                </a>
                <a href="/admin/home-service" class="nav-link {{ request()->is('admin/home-service') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i> Kelola Home Service
                </a>
                <a href="/admin/layanan-home-service" class="nav-link {{ request()->is('admin/layanan-home-service') ? 'active' : '' }}">
                    <i class="bi bi-tools"></i> Layanan Home Service
                </a>
                <a href="/admin/data-layanan" class="nav-link {{ request()->is('admin/data-layanan*') ? 'active' : '' }}">
                    <i class="bi bi-list-check"></i> Data Layanan
                </a>

                <a href="/admin/laporan-mekanik" class="nav-link {{ request()->is('admin/laporan-mekanik*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i> Laporan Mekanik
                </a>
                <a href="/admin/laporan-keuangan" class="nav-link {{ request()->is('admin/laporan-keuangan') ? 'active' : '' }}">
                    <i class="bi bi-currency-exchange"></i> Laporan Keuangan
                </a>

                <a href="/admin/konfirmasi-pembayaran" class="nav-link {{ request()->is('admin/konfirmasi-pembayaran') ? 'active' : '' }}">
                    <i class="bi bi-check2-circle"></i> Konfirmasi Pembayaran
                </a>
                <a href="/admin/stok-gudang" class="nav-link {{ request()->is('admin/stok-gudang') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Stok Gudang
                </a>
            </aside>
        @endif

        <main class="main-content">
            <div class="top-actions">
                <div>
                    <h1>@yield('heading','Dashboard Mekanik')</h1>
                    <div style="color:#64748b; font-size:14px;">@yield('subheading','Daftar booking untuk kode booking')</div>
                </div>
                <div class="logout">
                    <a href="{{ route('logout') }}">Logout</a>
                </div>
            </div>

            <div class="card">

            {{-- Flash message untuk notifikasi aksi admin --}}
            @if(session('success'))
                <div style="margin:16px 16px 0; padding:12px 14px; background:#ecfdf5; border:1px solid rgba(16,185,129,.25); border-radius:12px; color:#065f46; font-weight:900;">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="margin:16px 16px 0; padding:12px 14px; background:#fef2f2; border:1px solid rgba(239,68,68,.25); border-radius:12px; color:#991b1b; font-weight:900;">
                    ❌ {{ session('error') }}
                </div>
            @endif

@yield('content')

            </div>
        </main>
    </div>
</body>
</html>

