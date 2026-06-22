<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingUploadController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

Route::get('/', function () {
    return view('auth.login');
});

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('auth.login');
});

Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| REGISTER
|--------------------------------------------------------------------------
*/

Route::get('/register', function () {
    return view('auth.register');
});

Route::post('/register', [AuthController::class, 'register']);

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/admin/dashboard', function () {
    if (!session('role') || session('role') !== 'admin') {
        abort(403);
    }

    return app(\App\Http\Controllers\AdminBookingController::class)->index();
});

Route::get('/admin/laporan-mekanik', [\App\Http\Controllers\MechanicReportController::class, 'index']);
Route::get('/admin/laporan-mekanik/detail/{kodeBooking}', [\App\Http\Controllers\MechanicReportController::class, 'showDetail']);
Route::post('/admin/laporan-mekanik/{kodeBooking}/konfirmasi-biaya', [\App\Http\Controllers\MechanicReportController::class, 'confirmCost']);

Route::get('/admin/laporan-keuangan', [\App\Http\Controllers\FinancialReportController::class, 'index'])->name('admin.laporan-keuangan');

// Backward compatibility (avoid 404 if trailing slash used)
Route::get('/admin/laporan-keuangan/', [\App\Http\Controllers\FinancialReportController::class, 'index'])->name('admin.laporan-keuangan.trailing');

Route::get('/admin/laporan-keuangan/download-harian', [\App\Http\Controllers\FinancialReportController::class, 'downloadHarian']);
Route::get('/admin/laporan-keuangan/download-bulanan', [\App\Http\Controllers\FinancialReportController::class, 'downloadBulanan']);


Route::get('/mekanik/dashboard', [\App\Http\Controllers\MechanicServiceController::class, 'showDashboard']);

// Riwayat pengerjaan mekanik
Route::get('/mekanik/riwayat-pengerjaan', [\App\Http\Controllers\MechanicServiceController::class, 'showHistory']);
Route::get('/mekanik/riwayat-pengerjaan/{kodeBooking}', [\App\Http\Controllers\MechanicReportController::class, 'showDetail'])->name('mekanik.riwayat.detail');







Route::post('/mekanik/{kodeBooking}/submit-laporan', [\App\Http\Controllers\MechanicServiceController::class, 'submitTotalRepair'])->name('mekanik.submit-laporan');

Route::post('/mekanik/{kodeBooking}/upload-bukti-kerja', [\App\Http\Controllers\MechanicServiceController::class, 'uploadWorkProof'])->name('mekanik.upload-bukti-kerja');

Route::post('/mekanik/{kodeBooking}/progress-update', [\App\Http\Controllers\MechanicServiceController::class, 'submitProgressUpdate'])->name('mekanik.progress-update');

Route::post('/mekanik/{kodeBooking}/update-status', [\App\Http\Controllers\MechanicServiceController::class, 'updateWorkStatus'])->name('mekanik.update-status');


Route::get('/admin/home-service', function () {
    if (!session('role') || session('role') !== 'admin') {
        abort(403);
    }

    $bookingsHomeService = \App\Models\Booking::whereNotNull('alamat')
        ->orderBy('created_at', 'desc')
        ->limit(50)
        ->get();

    return view('admin.home-service-merge', compact('bookingsHomeService'));
});

Route::get('/admin/teknisi', function () {
    if (!session('role') || session('role') !== 'admin') {
        abort(403);
    }

    $mekaniks = \App\Models\User::where('role', 'mekanik')
        ->orderBy('name')
        ->get();

    // Ambil booking bengkel (alamat null) + home service (alamat terisi)
    $bookingsBengkel = \App\Models\Booking::with('mechanic')
        ->whereNull('alamat')
        ->whereIn('status', ['menunggu_pembayaran', 'pembayaran_dikonfirmasi', 'dikirim_ke_mekanik', 'sedang_dikerjakan', 'butuh_konfirmasi_biaya', 'selesai'])
        ->orderBy('assigned_at', 'desc')
        ->limit(100)
        ->get();

    $bookingsHomeService = \App\Models\Booking::with('mechanic')
        ->whereNotNull('alamat')
        ->whereIn('status', ['menunggu_pembayaran', 'pembayaran_dikonfirmasi', 'dikirim_ke_mekanik', 'sedang_dikerjakan', 'butuh_konfirmasi_biaya', 'selesai'])
        ->orderBy('assigned_at', 'desc')
        ->limit(100)
        ->get();

    $bookings = $bookingsBengkel->concat($bookingsHomeService)->sortByDesc('assigned_at');

    return view('admin.teknisi', compact('bookings', 'mekaniks'));
});

// Add new mechanic from admin UI
Route::post('/admin/teknisi/tambah', function (\Illuminate\Http\Request $request) {
    if (!session('role') || session('role') !== 'admin') {
        abort(403);
    }

    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255|unique:users,email',
        'no_hp' => 'nullable|string|max:50',
        'password' => 'required|string|min:8',
    ]);

    \App\Models\User::create([
        'name' => $data['name'],
        'email' => $data['email'] ?? null,
        'no_hp' => $data['no_hp'] ?? null,
        'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
        'plat_nomor' => null,
        'role' => 'mekanik',
    ]);

    return redirect('/admin/teknisi')->with('success', 'Mekanik berhasil ditambahkan.');
});


Route::get('/admin/stok-gudang', function (Request $request) {
    if (!session('role') || session('role') !== 'admin') {
        abort(403);
    }

    // default demo items
    $default = [
        ['id' => 'PRT-001', 'name' => 'Oli MPX2 0.8 Liter', 'category' => 'Pelumas', 'stock' => 24, 'unit' => 'Botol', 'price' => 65000],
        ['id' => 'PRT-002', 'name' => 'Kampas Rem Depan Vario', 'category' => 'Pengereman', 'stock' => 8, 'unit' => 'Set', 'price' => 75000],
        ['id' => 'PRT-003', 'name' => 'Timbal Balancing', 'category' => 'Roda', 'stock' => 45, 'unit' => 'pcs', 'price' => 15000],
        ['id' => 'PRT-004', 'name' => 'Pentil Ban Tubeless', 'category' => 'Roda', 'stock' => 3, 'unit' => 'pcs', 'price' => 10000],
        ['id' => 'PRT-005', 'name' => 'Aki GS Astra YTZ6V', 'category' => 'Kelistrikan', 'stock' => 12, 'unit' => 'Unit', 'price' => 285000],
    ];

    $sessionItems = session('stok_items', []);
    // merge default + session (session items appended after default)
    $items = array_values(array_merge($default, $sessionItems));

    $q = $request->query('q');
    if ($q) {
        $items = array_filter($items, function ($it) use ($q) {
            return stripos($it['name'], $q) !== false || stripos($it['id'], $q) !== false;
        });
        $items = array_values($items);
    }

    return view('admin.stok-gudang', compact('items', 'q'));
});

// Add new stock item (demo: stored in session)
Route::post('/admin/stok-gudang/add', function (Request $request) {
    if (!session('role') || session('role') !== 'admin') {
        abort(403);
    }

    // Demo: stored in session


    $data = $request->validate([
        'id' => 'required|string',
        'name' => 'required|string',
        'category' => 'nullable|string',
        'stock' => 'required|integer',
        'unit' => 'nullable|string',
        'price' => 'required|integer',
    ]);

    $session = session('stok_items', []);
    $session[] = $data;
    session(['stok_items' => $session]);

    return redirect('/admin/stok-gudang')->with('success', 'Barang baru berhasil ditambahkan (demo).');
});

// Edit stock item (demo: stored in session)
Route::post('/admin/stok-gudang/edit', function (Request $request) {
    if (!session('role') || session('role') !== 'admin') {
        abort(403);
    }

    $data = $request->validate([
        'id' => 'required|string',
        'name' => 'required|string',
        'category' => 'nullable|string',
        'stock' => 'required|integer',
        'unit' => 'nullable|string',
        'price' => 'required|integer',
    ]);

    $session = session('stok_items', []);
    $found = false;
    foreach ($session as &$item) {
        if (($item['id'] ?? null) === $request->input('id')) {
            $item = [
                'id' => $data['id'],
                'name' => $data['name'],
                'category' => $data['category'] ?? '',
                'stock' => intval($data['stock']),
                'unit' => $data['unit'] ?? '',
                'price' => intval($data['price']),
            ];
            $found = true;
            break;
        }
    }
    unset($item);

    // if not found in session, append (demo behavior)
    if (!$found) {
        $session[] = [
            'id' => $data['id'],
            'name' => $data['name'],
            'category' => $data['category'] ?? '',
            'stock' => intval($data['stock']),
            'unit' => $data['unit'] ?? '',
            'price' => intval($data['price']),
        ];
    }

    session(['stok_items' => $session]);
    return redirect('/admin/stok-gudang')->with('success', 'Barang berhasil diedit (demo).');
});

// Delete stock item (demo: stored in session)
Route::post('/admin/stok-gudang/delete', function (Request $request) {
    if (!session('role') || session('role') !== 'admin') {
        abort(403);
    }

    $data = $request->validate([
        'id' => 'required|string',
    ]);

    $session = session('stok_items', []);
    $session = array_values(array_filter($session, function ($item) use ($data) {
        return ($item['id'] ?? null) !== $data['id'];
    }));

    session(['stok_items' => $session]);
    return redirect('/admin/stok-gudang')->with('success', 'Barang berhasil dihapus (demo).');
});

// Import CSV (expects header: id,name,category,stock,unit,price)
Route::post('/admin/stok-gudang/import', function (Request $request) {
    if (!session('role') || session('role') !== 'admin') {
        abort(403);
    }

    $file = $request->file('import_file');
    if ($file && $file->isValid()) {
        $handle = fopen($file->getRealPath(), 'r');
        $rows = [];
        $header = null;
        while (($data = fgetcsv($handle)) !== false) {
            if (!$header) {
                $header = $data;
                continue;
            }
            $row = array_combine($header, $data);
            if ($row) {
                $rows[] = [
                    'id' => $row['id'] ?? uniqid('PRT-'),
                    'name' => $row['name'] ?? '',
                    'category' => $row['category'] ?? '',
                    'stock' => intval($row['stock'] ?? 0),
                    'unit' => $row['unit'] ?? '',
                    'price' => intval($row['price'] ?? 0),
                ];
            }
        }
        fclose($handle);

        $session = session('stok_items', []);
        $session = array_merge($session, $rows);
        session(['stok_items' => $session]);
    }

    return redirect('/admin/stok-gudang')->with('success', 'Import selesai (demo).');
});

// Export CSV of current items
Route::get('/admin/stok-gudang/export', function () {
    if (!session('role') || session('role') !== 'admin') {
        abort(403);
    }

    $default = [
        ['id' => 'PRT-001', 'name' => 'Oli MPX2 0.8 Liter', 'category' => 'Pelumas', 'stock' => 24, 'unit' => 'Botol', 'price' => 65000],
        ['id' => 'PRT-002', 'name' => 'Kampas Rem Depan Vario', 'category' => 'Pengereman', 'stock' => 8, 'unit' => 'Set', 'price' => 75000],
        ['id' => 'PRT-003', 'name' => 'Timbal Balancing', 'category' => 'Roda', 'stock' => 45, 'unit' => 'pcs', 'price' => 15000],
        ['id' => 'PRT-004', 'name' => 'Pentil Ban Tubeless', 'category' => 'Roda', 'stock' => 3, 'unit' => 'pcs', 'price' => 10000],
        ['id' => 'PRT-005', 'name' => 'Aki GS Astra YTZ6V', 'category' => 'Kelistrikan', 'stock' => 12, 'unit' => 'Unit', 'price' => 285000],
    ];

    $sessionItems = session('stok_items', []);
    $items = array_values(array_merge($default, $sessionItems));

    $response = new StreamedResponse(function () use ($items) {
        $out = fopen('php://output', 'w');
        fputcsv($out, ['id', 'name', 'category', 'stock', 'unit', 'price']);
        foreach ($items as $it) {
            fputcsv($out, [$it['id'], $it['name'], $it['category'], $it['stock'], $it['unit'], $it['price']]);
        }
        fclose($out);
    });

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="stok-gudang-export.csv"');
    return $response;
});







Route::get('/customer/dashboard', function () {
    $customerId = session('id_user');
    if (!$customerId || session('role') !== 'customer') {
        abort(403);
    }

    $repairInvoiceBooking = \App\Models\Booking::where('user_id', $customerId)
        ->whereIn('status', ['menunggu_pembayaran', 'menunggu_pembayaran_lunas', 'menunggu_pembayaran lunas', 'menunggu_pembayaran_final', 'lunas', 'Paid (Lunas)', 'selesai'])
        ->orderBy('updated_at', 'desc')
        ->first();



    $showRepairComplete = $repairInvoiceBooking !== null;

    return view('customer.dashboard', compact('showRepairComplete', 'repairInvoiceBooking'));
});

Route::get('/customer/repair-invoice/{kodeBooking}', [\App\Http\Controllers\CustomerHomeServiceController::class, 'showRepairInvoice'])->name('customer.repair-invoice');
Route::get('/customer/riwayat-perbaikan', [\App\Http\Controllers\CustomerHomeServiceController::class, 'showRepairHistory'])->name('customer.showRepairHistory');
Route::get('/customer/riwayat-nota-lunas', [\App\Http\Controllers\CustomerHomeServiceController::class, 'showRepairInvoiceLunas'])->name('customer.riwayat-nota-lunas');



/*
|--------------------------------------------------------------------------
| SERVICE
|--------------------------------------------------------------------------
*/

Route::get('/kunjungi-bengkel', function () {
    return view('services.kunjungi-bengkel');
});

Route::get('/home-service', [\App\Http\Controllers\HomeServiceController::class, 'showForm']);
Route::post('/home-service', [\App\Http\Controllers\HomeServiceController::class, 'store'])->name('home-service.store');
Route::get('/admin/home-service/detail/{kodeBooking}', [\App\Http\Controllers\HomeServiceController::class, 'showDetail'])->name('home-service.detail');
Route::get('/admin/home-service/bukti-dp/{kodeBooking}', [\App\Http\Controllers\HomeServiceController::class, 'showDpProof'])->name('home-service.bukti-dp');

Route::get('/customer/home-service', [\App\Http\Controllers\CustomerHomeServiceController::class, 'showMyBookings'])->name('customer.home-service');
Route::get('/customer/home-service/detail/{kodeBooking}', [\App\Http\Controllers\CustomerHomeServiceController::class, 'showDetail'])->name('customer.home-service.detail');

Route::post('/admin/home-service/confirm-payment/{kodeBooking}', [\App\Http\Controllers\HomeServiceController::class, 'confirmPayment'])->name('home-service.confirm');
Route::post('/admin/home-service/send-to-mechanic/{kodeBooking}', [\App\Http\Controllers\HomeServiceController::class, 'sendToMechanic'])->name('home-service.sendToMechanic');

Route::get('/payment/qris', [\App\Http\Controllers\BookingUploadController::class, 'showQris'])->name('payment.qris');

Route::get('/booking/upload-bukti', [BookingUploadController::class, 'showUpload'])->name('booking.showUpload');
Route::post('/booking/upload-bukti', [BookingUploadController::class, 'uploadBukti'])->name('booking.upload-bukti');

Route::post('/admin/home-service/send-final-invoice/{kodeBooking}', [\App\Http\Controllers\HomeServiceController::class, 'sendFinalInvoice'])->name('home-service.send-final-invoice');
Route::post('/admin/home-service/confirm-full-payment/{kodeBooking}', [\App\Http\Controllers\HomeServiceController::class, 'confirmFullPayment'])->name('home-service.confirm-full-payment');

// Admin konfirmasi DP (masuk ke menu Konfirmasi Pembayaran agar antrian DP juga terlihat)
Route::post('/admin/konfirmasi-pembayaran/konfirmasi-dp/{kodeBooking}', function (string $kodeBooking) {

    if (!session('role') || session('role') !== 'admin') {
        abort(403);
    }

    $booking = \App\Models\Booking::where('kode_booking', $kodeBooking)->first();
    if (!$booking) {
        abort(404);
    }

    // hanya boleh dari status menunggu_konfirmasi_bukti (DP)
    if ($booking->status !== 'menunggu_konfirmasi_bukti') {
        abort(422, 'Booking belum dalam status menunggu konfirmasi bukti DP.');
    }

    // proses konfirmasi DP: status menjadi pembayaran_dikonfirmasi
    $booking->status = 'pembayaran_dikonfirmasi';
    $booking->save();

    \App\Models\PaymentHistory::create([
        'kode_booking' => $booking->kode_booking,
        'action' => 'confirm_dp',
        'amount' => null,
        'remarks' => 'Pembayaran DP berhasil dikonfirmasi oleh admin (masuk antrian Konfirmasi Pembayaran).',
        'performed_by' => auth()->user()?->name ?? 'admin',
    ]);

    return redirect()->route('admin.konfirmasi-pembayaran')->with('success', 'Pembayaran DP berhasil dikonfirmasi.');
})->name('admin.konfirmasi-pembayaran.konfirmasi-dp');


Route::post('/booking/confirm-payment', [BookingController::class, 'confirmPayment'])->name('booking.confirm');

// Upload pelunasan (bukti pembayaran final) - halaman & route khusus
Route::get('/booking/upload-pelunasan', [\App\Http\Controllers\PelunasanUploadController::class, 'showUploadPelunasan'])->name('booking.showUploadPelunasan');
Route::post('/booking/pelunasan/upload-bukti', [\App\Http\Controllers\PelunasanUploadController::class, 'uploadPelunasanBukti'])->name('pelunasan.upload-bukti');

// Halaman QRIS pembayaran pelunasan (menu dari nota)
Route::get('/booking/payment-qris-pelunasan/{kode_booking}', [\App\Http\Controllers\PelunasanPaymentQrisController::class, 'show'])->name('booking.payment-qris-pelunasan');
Route::post('/booking', [BookingController::class, 'store']);


// Route::get('/dashboard', [BookingController::class, 'index']);
Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');

// Halaman sukses booking
Route::get('/booking/sukses', function () {
    return view('customer.booking-sukses');
})->name('booking.sukses');


Route::post('/booking/delete', [\App\Http\Controllers\BookingDeleteController::class, 'delete']);

// Menampilkan halaman upload bukti pelunasan
Route::get('/pelunasan/upload/{kode_booking}', [\App\Http\Controllers\PelunasanUploadController::class, 'showUploadPelunasan'])->name('pelunasan.form-upload');

// Backward compatibility: beberapa link lama memakai query string (?kode_booking=...)
// Contoh: /upload-pelunasan?kode_booking=BC-XXXX
Route::get('/upload-pelunasan', [\App\Http\Controllers\PelunasanUploadController::class, 'showUploadPelunasanQuery'])->name('pelunasan.upload-pelunasan-legacy');




// Memproses file upload kiriman dari form pembayarannya
Route::post('/pelunasan/upload', [\App\Http\Controllers\PelunasanUploadController::class, 'uploadPelunasanBukti'])->name('pelunasan.upload-bukti');

// Admin: Konfirmasi Pembayaran / Verifikasi Pelunasan Final
Route::get('/admin/konfirmasi-pembayaran', [\App\Http\Controllers\AdminKonfirmasiPembayaranController::class, 'index'])->name('admin.konfirmasi-pembayaran');

// =====================
// ADMIN: MASTER JASA LAYANAN
// =====================
Route::get('/admin/data-layanan', [\App\Http\Controllers\AdminServiceController::class, 'index'])->name('admin.data-layanan.index');
Route::get('/admin/data-layanan/edit/{id}', [\App\Http\Controllers\AdminServiceController::class, 'edit'])->name('admin.data-layanan.edit');
Route::post('/admin/data-layanan/store', [\App\Http\Controllers\AdminServiceController::class, 'store'])->name('admin.data-layanan.store');
Route::post('/admin/data-layanan/update/{id}', [\App\Http\Controllers\AdminServiceController::class, 'update'])->name('admin.data-layanan.update');
Route::post('/admin/data-layanan/delete/{id}', [\App\Http\Controllers\AdminServiceController::class, 'destroy'])->name('admin.data-layanan.destroy');

Route::post('/admin/konfirmasi-pembayaran/{kodeBooking}/tolak', [\App\Http\Controllers\AdminKonfirmasiPembayaranController::class, 'reject'])->name('admin.konfirmasi-pembayaran.tolak');
Route::post('/admin/konfirmasi-pembayaran/{kodeBooking}/confirm-final', [\App\Http\Controllers\AdminKonfirmasiPembayaranController::class, 'confirmFinalPayment'])->name('admin.konfirmasi-pembayaran.confirm-final');
Route::post('/pelunasan/upload-bukti', [\App\Http\Controllers\PelunasanUploadController::class, 'uploadPelunasanBukti'])->name('pelunasan.upload-bukti');

// (Customer) Repair Invoice route is already defined earlier: /customer/repair-invoice/{kodeBooking}
// If you see “repair-invoice not found”, ensure you are using the correct URL /customer/repair-invoice/{kodeBooking}
