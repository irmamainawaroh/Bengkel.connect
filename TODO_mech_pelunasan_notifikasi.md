# TODO - Notifikasi Pelunasan (Customer ↔ Admin)

## Step 1: Pastikan alur existing
- [x] Customer upload bukti pelunasan final → status jadi `menunggu_konfirmasi_bukti_final`
- [x] Kirim email ke admin saat upload (PaymentProofSubmitted)
- [x] Admin konfirmasi pelunasan final → status jadi `lunas`

## Step 2: Tambahkan email notifikasi ke customer saat status `lunas`
- [ ] Buat Mail class + view: `FinalPaymentConfirmedToCustomer`
- [ ] Di `HomeServiceController@confirmFullPayment`, setelah booking diset `lunas`, kirim email ke customer

## Step 3: Tampilkan notifikasi “Tagihan anda sudah lunas” di halaman customer
- [ ] Modifikasi `resources/views/customer/repair-invoice.blade.php` agar saat `$booking->status === 'lunas'` tampil banner jelas

## Step 4: Jalankan pengecekan manual
- [ ] Customer upload bukti → admin menerima email
- [ ] Admin konfirmasi → customer menerima email + banner status lunas terlihat

