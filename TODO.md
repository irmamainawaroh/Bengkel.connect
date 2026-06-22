# TODO - Perbaiki Login Email/Password

## Step 1
- [ ] Edit `app/Http/Controllers/AuthController.php`:
  - Normalisasi input `email` (trim + lowercase) sebelum query.

## Step 2
- [ ] Edit `app/Http/Controllers/AuthController.php`:
  - Diagnostik aman (logging) saat login gagal untuk mengetahui apakah user ditemukan dan format hash tersimpan.

## Step 3
- [ ] Edit `app/Http/Controllers/AuthController.php`:
  - Backward compatibility hanya jika terdeteksi password tersimpan plaintext dari seed/import lama.

## Step 4
- [ ] Jalankan manual test login untuk 1 akun yang sebelumnya gagal.

