# TODO - Tambah Kotak "Layanan lain" & "Jenis kendaraan lain" di Booking

- [ ] Identifikasi UI bagian form customer pada `resources/views/customer/dashboard.blade.php` untuk:
  - [ ] Booking Kunjungan Bengkel ("Pilih Layanan" + "Jenis Kendaraan")
  - [ ] Booking Home Service ("Pilih Layanan" + "Jenis Kendaraan")
- [ ] Tambahkan textarea di bawah dropdown layanan:
  - [ ] Label: "Layanan lain"
  - [ ] Hanya aktif/terlihat saat option "Lainnya (Opsional)" dipilih (jika ada JS)
  - [ ] Nama input mengikuti field backend (`layanan` atau strategi pemetaan yang sudah ada)
- [ ] Tambahkan textarea di bawah dropdown jenis kendaraan:
  - [ ] Label: "Jenis kendaraan lain"
  - [ ] Hanya aktif/terlihat saat ada option "Lainnya" (akan ditambahkan jika belum ada)
  - [ ] Nama input mengikuti field backend (`kendaraan` atau strategi pemetaan)
- [ ] Pastikan tidak mengganggu validasi backend yang saat ini mewajibkan `layanan` dan `kendaraan`.
- [ ] Lakukan test manual:
  - [ ] Pilih layanan selain "Lainnya" -> submit berhasil
  - [ ] Pilih "Lainnya" lalu isi textarea layanan lain -> submit berhasil
  - [ ] Pilih jenis kendaraan selain "Lain" -> submit berhasil
  - [ ] Pilih opsi "lainnya" jenis kendaraan (jika ditambahkan) -> submit berhasil dengan isi textarea

