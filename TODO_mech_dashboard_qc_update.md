# TODO - Update CHECKLIST QUALITY CONTROL (Mekanik)

- [x] Update teks checklist QC di `resources/views/admin/mekanik/dashboard.blade.php` menjadi 4 poin:
  1) Verifikasi Keluhan
  2) Kekencangan & Torsi
  3) Kebersihan
  4) Uji Jalan (Road Test)
- [x] Tambahkan/ubah `qc_items` hidden menjadi key baru: `keluhan,kekencangan,kebersihan,roadtest`
- [x] Sesuaikan logic JavaScript `resetFinalSummary()` dan `applyDynamicQC()` agar default checklist 4 item baru
- [x] Pastikan tampilan tidak error dan tombol Reset mengembalikan checklist



