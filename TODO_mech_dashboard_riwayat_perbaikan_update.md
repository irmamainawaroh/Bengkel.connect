# TODO: Riwayat perbaikan & konfirmasi pelunasan (Admin)

- [ ] Edit `HomeServiceController@confirmFullPayment` agar setelah admin mengklik **Setujui & Nyatakan Lunas**:
  - [ ] Update `bookings.status` menjadi `Paid (Lunas)`
  - [ ] Set `bookings.total_biaya_perbaikan` menjadi `0`
  - [ ] (Opsional) set field lain yang relevan (mis. `lunas_at` sudah ada)
  - [ ] Tetap buat `PaymentHistory` record.
- [ ] Buat menu/halaman **Riwayat Perbaikan** untuk customer (mis. `/customer/riwayat-perbaikan`) yang menampilkan histori booking/perbaikan.
- [ ] Hubungkan tombol/menu di layout customer ke halaman riwayat.
- [ ] Pastikan view customer menampilkan status `Paid (Lunas)` dan total Rp 0 saat booking lunas.
- [ ] Jalankan test manual:
  - [ ] Upload pelunasan final oleh customer
  - [ ] Admin verifikasi dan klik `Setujui & Nyatakan Lunas`
  - [ ] Cek DB: status berubah + total jadi 0
  - [ ] Buka halaman riwayat perbaikan customer: tampil dengan benar.

