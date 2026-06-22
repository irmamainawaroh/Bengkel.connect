<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Mekanik - BengkelConnect')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #fafafa; color: #1e293b; }

        /* Modal konfirmasi logout (Mekanik/Admin) */
        .confirm-logout-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.40);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            padding: 20px;
        }
        .confirm-logout-overlay.active { display: flex; }
        .confirm-logout-card {
            width: min(520px, 100%);
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.25);
            border: 1px solid rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }
        .confirm-logout-header {
            background: linear-gradient(90deg, rgba(220, 38, 38, 0.16), rgba(204, 58, 43, 0.10));
            padding: 22px 24px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }
        .confirm-logout-badge {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fee2e2;
            color: #cc3a2b;
            font-size: 20px;
            flex: 0 0 auto;
        }
        .confirm-logout-header h2 {
            margin: 0;
            font-size: 20px;
            color: #0f172a;
            font-weight: 800;
            line-height: 1.25;
        }
        .confirm-logout-header p {
            margin: 6px 0 0;
            color: #475569;
            font-size: 13px;
            line-height: 1.6;
        }
        .confirm-logout-body { padding: 18px 24px 22px; }
        .confirm-logout-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }
        .btn-confirm-logout {
            border: none;
            cursor: pointer;
            border-radius: 14px;
            padding: 12px 16px;
            font-weight: 800;
            transition: transform 0.12s ease, opacity 0.2s ease;
            min-width: 140px;
        }
        .btn-confirm-logout:active { transform: translateY(1px); }
        .btn-logout-cancel { background: #e2e8f0; color: #0f172a; }
        .btn-logout-confirm { background: #cc3a2b; color: #fff; }
        .btn-confirm-logout:hover { opacity: 0.92; }

        .container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
            align-items: start;
        }

        .main-content { display: flex; flex-direction: column; gap: 18px; }

        .top-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .logout a { color: #dc2626; text-decoration: none; font-weight: 700; }

        h1 { font-size: 28px; font-weight: 700; margin-bottom: 6px; }

        .subtitle { color:#64748b; font-size:14px; }

        .card {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 16px;
            overflow: hidden;
            padding: 18px;
        }

        @yield('styles')
    </style>
</head>
<body>
    <div class="container">
        <main class="main-content">
            <div class="top-actions">
                <div>
                    <h1>@yield('heading','Dashboard Mekanik')</h1>
                    <div class="subtitle">@yield('subheading','Daftar booking untuk kode booking')</div>
                </div>
                <div class="logout" style="display:flex; gap:12px; align-items:center;">
                    <a href="/mekanik/riwayat-pengerjaan" style="color:#0f172a; text-decoration:none; font-weight:900; padding:10px 12px; border-radius:12px; background:#e2e8f0;">
                        Riwayat Pengerjaan
                    </a>
                    <a href="{{ route('logout') }}" id="logoutLinkMekanik" style="color:#dc2626; text-decoration:none; font-weight:700;">
                        Logout
                    </a>
                </div>
            </div>

            <div class="card">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Modal konfirmasi logout -->
    <div class="confirm-logout-overlay" id="logoutConfirmOverlay" aria-hidden="true">
        <div class="confirm-logout-card" role="dialog" aria-modal="true" aria-labelledby="logoutConfirmTitle">
            <div class="confirm-logout-header">
                <div class="confirm-logout-badge">🚪</div>
                <div>
                    <h2 id="logoutConfirmTitle">Konfirmasi Logout</h2>
                    <p>Anda akan keluar dari akun mekanik/admin. Pastikan tidak ada data yang belum dikirim.</p>
                </div>
            </div>
            <div class="confirm-logout-body">
                <div class="confirm-logout-actions">
                    <button type="button" class="btn-confirm-logout btn-logout-cancel" id="logoutCancelBtn">Batal</button>
                    <button type="button" class="btn-confirm-logout btn-logout-confirm" id="logoutConfirmBtn">Ya, Logout</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const logoutLink = document.getElementById('logoutLinkMekanik');
            const overlay = document.getElementById('logoutConfirmOverlay');
            const cancelBtn = document.getElementById('logoutCancelBtn');
            const confirmBtn = document.getElementById('logoutConfirmBtn');

            if (!logoutLink || !overlay || !cancelBtn || !confirmBtn) return;

            const showLogoutConfirm = () => {
                overlay.classList.add('active');
                overlay.setAttribute('aria-hidden', 'false');
            };

            const hideLogoutConfirm = () => {
                overlay.classList.remove('active');
                overlay.setAttribute('aria-hidden', 'true');
            };

            // Pakai event capture agar tidak tertimpa handler lain
            logoutLink.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                showLogoutConfirm();
            }, true);


            cancelBtn.addEventListener('click', hideLogoutConfirm);

            confirmBtn.addEventListener('click', () => {
                window.location.href = logoutLink.href;
            });

            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) hideLogoutConfirm();
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') hideLogoutConfirm();
            });
        });
    </script>
</body>
</html>


