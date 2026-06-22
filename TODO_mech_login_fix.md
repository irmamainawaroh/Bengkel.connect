# TODO: Perbaiki Login Mekanik (Admin/teknisi tidak bisa login pakai password lama)

## Analisa yang sudah ada
- Login memakai `AuthController@login()`.
- Error yang muncul: **“Email atau password salah”**.
- Penyebab paling mungkin: password di tabel `users` untuk akun mekanik **tidak cocok** (bisa karena password lama berubah format/hash).

## Step yang harus dilakukan
1. Cek akun mekanik yang gagal di tabel `users`:
   - email cocok dengan input
   - kolom `role` (jika ada) harus `mekanik` (kalau tidak ada/beda, mekanik bisa gagal redirect/akses dashboard)
   - nilai `password` benar-benar sesuai:
     - jika plaintext lama: harus sama persis dengan password yang diinput
     - jika sudah hash: harus sesuai hash-nya
2. Jika tidak yakin password lama: lakukan reset password mekanik ke password baru (hash ulang) dan set role mekanik.
3. (Opsional tapi direkomendasikan) Tambahkan endpoint admin untuk reset password mekanik agar kejadian ini tidak berulang.
4. Tes ulang:
   - login mekanik dengan password baru
   - pastikan redirect ke `/mekanik/dashboard` dan halaman muncul.

