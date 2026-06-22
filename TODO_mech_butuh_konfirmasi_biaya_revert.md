# TODO: Revert status ke `butuh_konfirmasi_biaya`

- [ ] Update alur admin: tambahkan tombol/aksi revert pada halaman `resources/views/admin/laporan-mekanik-detail.blade.php` untuk memindahkan `menunggu_pembayaran_lunas` kembali ke `butuh_konfirmasi_biaya`.
- [ ] Implement endpoint controller (kemungkinan di `app/Http/Controllers/MechanicReportController.php`) untuk memproses revert.
- [ ] Sinkronkan field yang relevan (mis. tetap simpan `total_biaya_perbaikan` dan `laporan_perbaikan`, tapi kembalikan status saja).
- [ ] Pastikan guard/validasi status: hanya izinkan revert jika status saat ini `menunggu_pembayaran_lunas`.
- [ ] Update route `routes/web.php` jika endpoint baru dibutuhkan.
- [ ] Smoke test manual flow: mekanik upload → admin confirm cost → revert → pastikan status kembali dan UI/daftar antrean sesuai.
