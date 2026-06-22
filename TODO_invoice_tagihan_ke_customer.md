# TODO - Nota tagihan terkirim -> menunggu_pembayaran_lunas + notif customer

## Informasi yang sudah ditemukan
- Workflow admin kirim invoice total ada di `app/Http/Controllers/HomeServiceController.php` pada method `sendFinalInvoice()`.
- Saat ini `sendFinalInvoice()` meng-set:
  - `status = menunggu_pembayaran_final`
  - lalu redirect admin.
- Customer menerima email hanya pada `confirmFullPayment()` (status `lunas`) menggunakan `FinalPaymentConfirmedToCustomer`.
- Halaman invoice customer `showRepairInvoice()` sudah mengizinkan status `menunggu_pembayaran_lunas`.

## Perubahan yang dibutuhkan
1. Ubah `sendFinalInvoice()` agar status booking menjadi `menunggu_pembayaran_lunas` (bukan `menunggu_pembayaran_final`).
2. Pastikan saat admin mengirim nota tagihan/invoice, customer mendapat notifikasi (email) yang sesuai.
   - (Jika belum ada Mailable/template untuk “invoice sent / nota tagihan terkirim”, tambahkan.)
3. (Opsional untuk konsistensi UI) Pastikan view customer/route/proteksi status lain tidak mengunci invoice saat status berubah ke `menunggu_pembayaran_lunas`.

## Dependent files
- `app/Http/Controllers/HomeServiceController.php`
- (baru jika diperlukan) `app/Mail/<InvoiceSentToCustomer>.php`
- `resources/views/emails/<invoice-sent-template>.blade.php`
- (mungkin) `resources/views/admin/booking-detail.blade.php` untuk teks status agar sesuai.

## Testing
- Admin: klik aksi “sendFinalInvoice/nota tagihan terkirim” pada booking.
- Verifikasi status booking berubah ke `menunggu_pembayaran_lunas`.
- Verifikasi email notifikasi terkirim ke email customer.
- Customer: buka halaman `/customer/repair-invoice/{kodeBooking}` dan pastikan tampil.

