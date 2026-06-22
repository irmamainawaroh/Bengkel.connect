# TODO Payment Pelunasan Update

## Langkah 1: Setup status & skema data
- Tambah kolom `bukti_pelunasan_path` pada tabel `bookings` (migration)
- Pastikan model `Booking` (fillable/casts) mendukung kolom baru

## Langkah 2: Perubahan flow di backend
- Update `HomeServiceController::sendFinalInvoice()` supaya status jadi `menunggu_pembayaran_pelunasan` (bukan `menunggu_pembayaran_final`)
- Tambah method `HomeServiceController::confirmPelunasan($kodeBooking)`
- Update `HomeServiceController` routing/redirect sesuai flow pelunasan

## Langkah 3: Upload bukti pelunasan dari customer
- Update `BookingUploadController::uploadBukti()` supaya mendukung status `menunggu_pembayaran_pelunasan`
- Simpan file ke `bukti-pelunasan/...`
- Set status ke `menunggu_konfirmasi_bukti_pelunasan`
- Buat `PaymentHistory` action `upload_bukti_pelunasan`

## Langkah 4: Update routes
- Tambah route POST `/admin/home-service/confirm-pelunasan/{kodeBooking}` ke `confirmPelunasan`

## Langkah 5: Update UI admin
- Update `resources/views/admin/booking-detail.blade.php`
  - Tambah section "Bukti Pelunasan"
  - Tambah tombol "Konfirmasi Pelunasan" saat status `menunggu_konfirmasi_bukti_pelunasan`

## Langkah 6: Update UI customer
- Update `resources/views/customer/upload-bukti.blade.php`
  - label & pesan sesuai pelunasan
  - kondisi status baru `menunggu_pembayaran_pelunasan`.

## Langkah 7: Testing
- Jalankan migration
- Test alur:
  1) Admin kirim nota -> `menunggu_pembayaran_pelunasan`
  2) Customer upload bukti -> `menunggu_konfirmasi_bukti_pelunasan` + `bukti_pelunasan_path` terisi
  3) Admin konfirmasi pelunasan -> status `lunas` + riwayat pembayaran terisi

