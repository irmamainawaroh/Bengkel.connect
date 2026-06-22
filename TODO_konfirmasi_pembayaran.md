# TODO - Konfirmasi Pembayaran (Admin)

1. Tambah route GET/POST.
2. Tambah action controller untuk daftar transaksi (status menunggu_konfirmasi_bukti_final).
3. Tambah action controller untuk tolak (ubah status ke menunggu_pembayaran_final, set bukti_total_pembayaran_path = null).
4. Buat view `resources/views/admin/konfirmasi-pembayaran.blade.php`:
   - tabel daftar transaksi
   - modal pop-up (detail order + bukti + tombol terima/tolak)
5. Update sidebar admin: tambahkan menu menuju halaman baru.
6. Test manual di browser.

