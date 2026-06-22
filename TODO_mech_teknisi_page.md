# TODO - Halaman Kelola Teknisi (Admin)

## Informasi yang sudah ditemukan
- Halaman admin sudah ada: `resources/views/admin/teknisi.blade.php`.
- Route list teknisi ada di `routes/web.php`: `/admin/teknisi`.
- Form tambah teknisi sudah mengarah ke `/admin/teknisi/tambah` (tapi backend endpoint masih minimal).
- View saat ini masih banyak placeholder untuk: foto profil, keahlian/wilayah/status/rating dari DB.

## Rencana Perubahan (akan diimplementasikan setelah approval)

1. Rapikan struktur UI halaman `teknisi.blade.php`:
   - Samakan layout tabel + panel filter.
   - Tambahkan kolom/section manajemen penugasan (aktif/riwayat) berbasis data `bookings`.
2. Lengkapi data dari database untuk:
   - Keahlian & wilayah teknisi (ambil dari field yang tersedia di `users`/profile).
   - Status (available/busy/off/suspend) dari kombinasi booking aktif + flag nonaktif (jika ada).
   - Rating (gunakan field yang ada di project; jika tidak ada, tampilkan dash).
3. Implement action buttons:
   - Detail & Edit: buat modal atau halaman terpisah (prefer modal).
   - Nonaktifkan/Hapus: buat endpoint real (bukan placeholder) untuk toggle status/suspend.
4. Tambah fitur pencarian & filter:
   - Search by name/id.
   - Filter status, keahlian, wilayah (berbasis data yang benar dari DB).
5. Pastikan endpoint tambah teknisi sesuai form:
   - Simpan keahlian, area/wilayah, dan (jika ada) dokumen pendukung.
   - Validasi & redirect dengan flash message.

## Follow-up (testing)
- Jalankan route lokal dan pastikan:
  - Tabel terisi dari mekanik.
  - Filter bekerja.
  - Tombol tambah/nonaktif/edit berjalan tanpa error.

