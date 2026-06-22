# Progress - Revert status `menunggu_pembayaran_lunas` -> `butuh_konfirmasi_biaya`

- [x] Verifikasi form admin “Konfirmasi & Kirim ke Akun” mengarah ke `POST /admin/laporan-mekanik/{kodeBooking}/konfirmasi-biaya`
- [ ] Tambah tombol revert di `resources/views/admin/laporan-mekanik-detail.blade.php`
- [ ] Tambah route revert di `routes/web.php`
- [ ] Implement method revert di `app/Http/Controllers/MechanicReportController.php`
- [ ] Update guard dan redirect + flash message
- [ ] Smoke test manual

