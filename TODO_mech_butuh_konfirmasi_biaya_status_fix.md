# TODO_mech_butuh_konfirmasi_biaya_status_fix.md

- [ ] Update status workflow: admin hanya bisa kirim nota setelah biaya dikonfirmasi (butuh_konfirmasi_biaya -> menunggu_pembayaran_lunas) dan nota terkirim.
- [ ] Pastikan setelah nota dikirim, status booking menjadi `menunggu_pembayaran_lunas` (sekali) dan tidak kembali ke `butuh_konfirmasi_biaya`.
- [ ] Cek alur lain yang mengubah status otomatis supaya tidak mengganggu setelah invoice dikirim.
- [ ] (Opsional) Tambahkan guard agar `confirmCost` tidak mengirim nota dua kali.
- [ ] Jalankan test/cek manual endpoint terkait booking: `/admin/laporan-mekanik/{kodeBooking}/konfirmasi-biaya` dan halaman konfirmasi pembayaran.

