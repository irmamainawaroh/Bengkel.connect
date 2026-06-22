# TODO: Perbaiki status mekanik selesai -> butuh_konfirmasi_biaya (bukan menunggu_konfirmasi_bukti)

## Informasi yang ditemukan
- `MechanicServiceController@uploadWorkProof()` saat mekanik upload bukti kerja saat ini mengubah status ke **`butuh_konfirmasi_biaya`**.
- `updateWorkStatus()` hanya mengubah `sedang_dikerjakan` -> `menunggu_pembayaran_final`.

## Dugaan sumber bug
- Kemungkinan ada handler lain yang dipakai UI saat "update selesai" (mis. tombol yang tidak memanggil uploadWorkProof, atau ada mekanisme update lain yang masih set status ke `menunggu_konfirmasi_bukti`).

## Rencana edit
1. Cari implementasi route/handler yang berkaitan dengan:
   - upload bukti kerja mekanik
   - "update selesai" atau transisi status setelah selesai
   - perubahan status ke `menunggu_konfirmasi_bukti`
2. Jika ditemukan transisi yang salah, ganti mapping dari `menunggu_konfirmasi_bukti` -> `butuh_konfirmasi_biaya`.
3. Update validasi/allowed status agar transisi konsisten.
4. Jalankan test manual minimal:
   - mekanik upload bukti kerja -> pastikan status `butuh_konfirmasi_biaya`
   - cek tampilan dashboard/riwayat untuk label yang benar.

## Follow-up
- Jika perlu, tambahkan guard log/PaymentHistory untuk memudahkan debugging.

