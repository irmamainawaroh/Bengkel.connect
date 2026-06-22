# TODO Restore: Laporan Mekanik (Semula)

- [ ] Backup/restore state rapi (cek file target)
- [ ] Tentukan baseline “awal” (butuh versi lama). Jika tidak ada, minimal kembalikan agar UI/route kembali ke bentuk sederhana.
- [ ] Update file berikut sesuai baseline:
  - app/Http/Controllers/MechanicReportController.php
  - resources/views/admin/laporan-mekanik.blade.php
  - resources/views/admin/laporan-mekanik-detail.blade.php
  - (jika perlu) resources/views/admin/laporan-mekanik-detail/komponen terkait & routes/web.php
- [ ] Jalankan test smoke: buka /admin/laporan-mekanik dan /admin/laporan-mekanik/detail/{kodeBooking}
- [ ] Pastikan status workflow tidak rusak (confirm biaya mengarah ke halaman yang benar)

