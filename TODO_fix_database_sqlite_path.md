# TODO: Fix database.sqlite absolute path issue

## Goal
Memastikan aplikasi Laravel memakai path SQLite yang benar/ada sehingga error:
"Database file ... does not exist. Ensure this is an absolute path ..." tidak muncul.

## Plan langkah
1. Cek apakah `database/database.sqlite` benar-benar ada di repo.
2. Cek konfigurasi DB di `config/database.php` dan value `DB_CONNECTION`, `DB_DATABASE` pada `.env`.
3. Jika missing, pilih salah satu:
   - Pastikan file `database/database.sqlite` ada (buat kosong/restore) atau
   - Ubah `.env` agar `DB_DATABASE` mengarah ke lokasi yang ada (absolute path).
4. Jalankan `php artisan config:clear` dan `php artisan migrate` (opsional) untuk verifikasi.

## Status
- Belum dikerjakan

