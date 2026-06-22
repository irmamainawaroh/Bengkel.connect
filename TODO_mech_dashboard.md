# TODO - Kelola Teknisi (admin)

## Step 1 — Analisa struktur kode yang ada
- Telusuri `resources/views/admin/teknisi.blade.php` untuk melihat bagian tabel/aksi/modal/filter.
- Telusuri `routes/web.php` untuk memastikan endpoint untuk tambah teknisi.
- Telusuri model/field terkait (mis. `users` role mekanik, relasi `bookings`).

## Step 2 — Rancang perbaikan sesuai requirement halaman Kelola Teknisi
- Pastikan kolom utama sesuai: ID + foto, nama & kontak, spesialisasi/keahlian, status ketersediaan (available/busy/off/suspend), rating/performa.
- Pastikan tombol aksi: Detail, Edit, Hapus/Nonaktifkan mengarah ke route yang benar (bukan placeholder).
- Pastikan manajemen penugasan: tampilkan jumlah tiket aktif & riwayat pekerjaan (completed/canceled) per teknisi.
- Pastikan tombol + tambah teknisi membuka form yang benar dan tersimpan di backend.
- Pastikan filter & search berfungsi untuk data nyata (bukan UI placeholder).

## Step 3 — Implementasi backend (jika belum ada)
- Jika belum ada controller/route khusus kelola teknisi: buat controller untuk list/detail/edit/toggle status.
- Tambahkan field users yang dibutuhkan (keahlian, area, rating, status ketersediaan) bila belum ada.

## Step 4 — Implementasi frontend (Blade)
- Update `resources/views/admin/teknisi.blade.php` agar:
  - Data status & rating diambil dari backend.
  - Data spesialisasi/keahlian & area ditampilkan.
  - Bagian penugasan ditampilkan (jumlah tiket aktif & riwayat).
  - Tombol aksi mengarah ke route controller.

## Step 5 — Uji
- Akses `/admin/teknisi` dengan role admin.
- Tes tambah teknisi, filter, pencarian.
- Tes aksi detail/edit/nonaktif.


