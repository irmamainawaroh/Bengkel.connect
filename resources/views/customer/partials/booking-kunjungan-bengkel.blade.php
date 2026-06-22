{{-- Partial Booking Kunjungan Bengkel --}}
<div id="kunjungiBengkelForm">
    <form method="POST" action="{{ route('booking.store') }}">
        @csrf

        <div class="form-group">
            <label><i class="bi bi-tools"></i> Pilih Layanan</label>
            <select name="layanan" class="form-control" required>
                <option value="" disabled selected>-- Pilih Layanan --</option>
                <option value="Ganti Oli Mesin & Filter Oli">Ganti Oli Mesin & Filter Oli</option>
                <option value="Ganti Filter Udara & Filter Kabin">Ganti Filter Udara & Filter Kabin</option>
                <option value="Spooring & Balancing 4 Roda">Spooring & Balancing 4 Roda</option>
                <option value="Ganti Shockbreaker / Strut">Ganti Shockbreaker / Strut</option>
                <option value="Ganti Link Stabilizer / Tierod / Ball Joint">Ganti Link Stabilizer / Tierod / Ball Joint</option>
                <option value="Rotasi Ban">Rotasi Ban</option>
                <option value="Ganti Kampas Rem (Dispad / Brake Shoe)">Ganti Kampas Rem (Dispad / Brake Shoe)</option>
                <option value="Bubut Piringan Cakram (Disc Brake)">Bubut Piringan Cakram (Disc Brake)</option>
                <option value="Kuras & Ganti Minyak Rem (Brake Fluid)">Kuras & Ganti Minyak Rem (Brake Fluid)</option>
                <option value="Ganti/Kuras Oli Transmisi (Manual / Matic ATF/CVT)">Ganti/Kuras Oli Transmisi (Manual / Matic ATF/CVT)</option>
                <option value="Ganti Set Kopling (Clutch Kit - Manual)">Ganti Set Kopling (Clutch Kit - Manual)</option>
                <option value="Kalibrasi / Scan Transmisi Otomatis">Kalibrasi / Scan Transmisi Otomatis</option>
                <option value="Ganti Aki (Accu) + Cek Alternator">Ganti Aki (Accu) + Cek Alternator</option>
                <option value="Jamper Aki / Perbaikan Sekring & Kabel">Jamper Aki / Perbaikan Sekring & Kabel</option>
                <option value="Lainnya (Opsional)">Lainnya (Opsional)</option>
            </select>

            <div style="margin-top:12px;" data-layanan-lain-wrapper>
                <label style="display:flex; align-items:center; gap:8px; font-size:12px; font-weight:500; color:#495057; margin-bottom:6px;">
                    <i class="bi bi-chat-left-text"></i> Layanan Lain
                </label>
                <textarea name="layanan_lain" class="form-control" placeholder="Ketik layanan lain yang Anda butuhkan..."></textarea>
            </div>
        </div>

        <div class="form-group">
            <label><i class="bi bi-calendar3"></i> Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <div class="form-group">
            <label><i class="bi bi-clock"></i> Waktu</label>
            <select name="waktu" class="form-control" required>
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

        <div class="form-group">
            <label><i class="bi bi-person"></i> Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
        </div>

        <div class="form-group">
            <label><i class="bi bi-telephone"></i> Nomor Telepon</label>
            <input type="tel" name="telepon" class="form-control" placeholder="08xx-xxxx-xxxx" required>
        </div>

        <div class="form-group" data-kendaraan-form>
            <label><i class="bi bi-car-front"></i> Jenis Kendaraan</label>
            <select name="kendaraan" class="form-control" required>
                <option value="" disabled selected>-- Pilih Jenis --</option>
                <option value="Honda Brio">Honda Brio</option>
                <option value="Toyota Yaris">Toyota Yaris</option>
                <option value="Toyota Agya">Toyota Agya</option>
                <option value="Suzuki Ignis">Suzuki Ignis</option>
                <option value="Toyota Avanza">Toyota Avanza</option>
                <option value="Mitsubishi Xpander">Mitsubishi Xpander</option>
                <option value="Toyota Innova">Toyota Innova</option>
                <option value="Suzuki Ertiga">Suzuki Ertiga</option>
                <option value="Toyota Fortuner">Toyota Fortuner</option>
                <option value="Mitsubishi Pajero Sport">Mitsubishi Pajero Sport</option>
                <option value="Honda CR-V">Honda CR-V</option>
                <option value="Daihatsu Terios">Daihatsu Terios</option>
                <option value="Honda Civic">Honda Civic</option>
                <option value="Toyota Camry">Toyota Camry</option>
                <option value="Mercedes-Benz C-Class">Mercedes-Benz C-Class</option>
                <option value="Daihatsu Gran Max">Daihatsu Gran Max</option>
                <option value="Suzuki Carry">Suzuki Carry</option>
                <option value="Toyota Hilux">Toyota Hilux</option>
                <option value="Wuling Air EV">Wuling Air EV</option>
                <option value="Hyundai Ioniq 5">Hyundai Ioniq 5</option>
                <option value="Toyota Kijang Innova Zenix Hybrid">Toyota Kijang Innova Zenix Hybrid</option>
            </select>

            <div style="margin-top:12px;" data-kendaraan-lain-wrapper>
                <label style="display:flex; align-items:center; gap:8px; font-size:12px; font-weight:500; color:#495057; margin-bottom:6px;">
                    <i class="bi bi-car-front"></i> Jenis Kendaraan Lain
                </label>
                <textarea name="kendaraan_lain" class="form-control" placeholder="Ketik jenis kendaraan lain..."></textarea>
            </div>
        </div>

        <div class="form-group">
            <label><i class="bi bi-card-text"></i> Nomor Polisi</label>
            <input type="text" name="nopol" class="form-control" placeholder="B 1234 XYZ" required>
        </div>

        <div class="form-group">
            <label><i class="bi bi-pencil-square"></i> Catatan Tambahan (Perbaikan)</label>
            <textarea name="catatan" class="form-control" placeholder="Tambahkan informasi perbaikan jika diperlukan"></textarea>
        </div>

        <button type="submit" class="btn-submit btn-red">Konfirmasi Booking</button>
    </form>
</div>

