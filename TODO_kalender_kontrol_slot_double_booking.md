# TODO - Validasi Kalender Kontrol (Anti-Double Booking) - Slot (tanggal, waktu)

## Langkah implementasi
- [x] Tambah unique constraint DB untuk memastikan 1 slot = 1 booking per kombinasi (tanggal, waktu)
  - File: database/migrations/2026_06_09_000000_add_unique_slot_tanggal_waktu_to_bookings_table.php
- [x] Update HomeServiceController agar create booking dibungkus transaction dan error unique constraint ditangani dengan pesan "Slot penuh"
  - File: app/Http/Controllers/HomeServiceController.php
- [x] Update BookingController (route legacy) agar create booking dibungkus transaction dan error unique constraint ditangani dengan pesan "Slot penuh"
  - File: app/Http/Controllers/BookingController.php
- [ ] Jalankan `php artisan migrate`
- [ ] Test manual:
  - Booking cepat dari 2 browser dengan (tanggal, waktu) sama -> 1 sukses, 1 gagal dengan pesan slot penuh
  - Booking dengan (tanggal, waktu) berbeda -> sukses


