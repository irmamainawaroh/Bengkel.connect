<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Booking Home Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card p-4 shadow-sm">
            <h2 class="mb-3">Booking Home Service</h2>
            <p>Silakan pilih layanan dan isi data kendaraan Anda. Teknisi akan datang ke lokasi Anda.</p>

            <form method="POST" action="{{ route('home-service.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Pilih Layanan</label>
                    <select name="layanan" class="form-select" required>
                        <option value="" disabled selected>-- Pilih Layanan --</option>
                        <option value="Servis Berkala Home">Servis Berkala (Ganti Oli/Filter)</option>
                        <option value="Perbaikan Ringan Home">Perbaikan Ringan</option>
                        <option value="Pengecekan & Ganti Aki Home">Pengecekan & Ganti Aki</option>
                        <option value="Karburator / Sistem Bahan Bakar Home">Karburator / Sistem Bahan Bakar</option>
                        <option value="Perbaikan Rem Home">Perbaikan Rem</option>
                        <option value="Pengecekan Suspensi Home">Pengecekan Suspensi</option>
                        <option value="Lainnya (Opsional)">Lainnya (Opsional)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Kedatangan</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Waktu</label>
                    <select name="waktu" class="form-select" required>
                        <option value="" disabled selected>-- Pilih Waktu --</option>
                        <option value="08:00 WIB">08:00 WIB</option>
                        <option value="09:00 WIB">09:00 WIB</option>
                        <option value="10:00 WIB">10:00 WIB</option>
                        <option value="11:00 WIB">11:00 WIB</option>
                        <option value="12:00 WIB">12:00 WIB</option>
                        <option value="13:00 WIB">13:00 WIB</option>
                        <option value="14:00 WIB">14:00 WIB</option>
                        <option value="15:00 WIB">15:00 WIB</option>
                        <option value="16:00 WIB">16:00 WIB</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="tel" name="telepon" class="form-control" placeholder="08xx-xxxx-xxxx" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Kendaraan</label>
                    <input type="text" name="kendaraan" class="form-control" placeholder="Contoh: Toyota Avanza" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Polisi</label>
                    <input type="text" name="nopol" class="form-control" placeholder="B 1234 XYZ" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan Tambahan (Opsional)</label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Tuliskan keluhan atau permintaan khusus"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Konfirmasi Booking Home Service</button>
            </form>
        </div>
    </div>
</body>
</html>
