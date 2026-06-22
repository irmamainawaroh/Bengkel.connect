# TODO - Update UI Dashboard Mekanik (QC dinamis)

- [x] Update checklist QC pada final summary di `resources/views/admin/mekanik/dashboard.blade.php`:
  - [x] Mengubah checkbox dari hardcoded `checked disabled` menjadi checkbox dinamis (input hidden qc_items untuk submit UI).
  - [x] Menambahkan logic JavaScript `applyDynamicQC()` untuk mengaktifkan/menambah checklist berdasarkan `Jenis Layanan Utama` (`$booking->layanan`) dan tambahan perbaikan (recommended_parts).
  - [x] Memastikan `resetFinalSummary()` mengembalikan checklist ke default.
  - [x] Verifikasi syntax PHP (php -l) pada file admin dashboard.

- [ ] Terapkan perubahan yang sama ke `resources/views/mekanik/dashboard-layout.blade.php` (jika file ini dipakai sebagai sumber tampilan final summary pada deploy/versi lain).

