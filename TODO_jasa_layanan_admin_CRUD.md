# TODO: Menu Jasa Layanan (Admin - Bengkel Connect)

## Langkah 1: Tambah menu sidebar admin
- Update `resources/views/admin/dashboard-layout.blade.php` agar ada item sidebar:
  - Dashboard
  - Data Layanan
  - Pesanan Masuk
  - Pengguna

## Langkah 2: Backend CRUD master layanan (persist DB)
- Buat migration tabel `jasa_layanans` (atau nama yang konsisten):
  - id_jasa (string, unique)
  - nama_jasa (string)
  - estimasi_harga (integer)
  - is_default (opsional) / flag untuk kunci
  - timestamps
- Buat model `app/Models/JasaLayanan.php`
- Buat controller `app/Http/Controllers/AdminServiceController.php`
- Tambah route CRUD di `routes/web.php`.

## Langkah 3: UI CRUD
- Buat view `resources/views/admin/data-layanan.blade.php`:
  - Tabel daftar layanan
  - Tombol tambah layanan
  - Tombol edit/hapus
  - Kunci untuk `id_jasa = L00` (tidak bisa hapus)

## Langkah 4: Seed data default
- Pastikan record `L00` selalu ada (bisa dengan seeder atau logic upsert pada halaman/route/migration).

## Langkah 5: Testing manual
- Jalankan `php artisan migrate`.
- Buka `/admin/data-layanan`.
- Coba tambah/edit/hapus.
- Pastikan `L00` tidak bisa dihapus.

Status Progress:
- ✅ Buat TODO file pelaksanaan


