# Recheck: Booking detail not found

## Goal
Perbarui alur agar halaman booking detail customer aktif (tidak 404) dan memastikan route yang benar dipakai.

## Observations
- Route detail customer ada di:
  - GET `/customer/home-service/detail/{kodeBooking}`
  - Controller: `CustomerHomeServiceController@showDetail`

## Current Fix Status
- `showDetail()` dan `showMyBookings()` **tidak lagi** menyaring `alamat` (whereNotNull('alamat') sudah dihapus di commit sebelumnya).

## Next Debug Step
1. Pastikan link di UI memang menggunakan route customer (bukan route admin).
   - Periksa `resources/views/customer/home-service-bookings.blade.php`.
   - Pastikan URL memakai: `route('customer.home-service.detail', $booking->kode_booking)`.
2. Pastikan route name `'customer.home-service.detail'` memang sesuai di `routes/web.php`.
3. Jika masih 404, kemungkinan besar:
   - `kodeBooking` yang diklik tidak milik customer yang sedang login (user_id mismatch)
   - atau session `id_user` kosong/berbeda.
4. Tambahkan log sementara di `showDetail()` untuk menampilkan `kodeBooking`, `customerId`, dan hasil query (gunakan Laravel logger).

