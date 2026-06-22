# TODO: Dropdown/Select Sparepart untuk Mekanik (Searchable + Qty)

## Informasi yang sudah ditemukan
- Halaman mekanik saat ini: `resources/views/admin/mekanik/dashboard.blade.php`
- Backend mekanik yang ada: `app/Http/Controllers/MechanicServiceController.php`
- Route mekanik yang ada: `routes/web.php`
- Saat ini mekanik hanya mengirim `recommended_parts[]` melalui `progress-update`.
- Belum ada UI/form “Pilih Suku Cadang + Qty + Tambah” di repo.

## Tujuan implementasi
1. Mekanik bisa memilih sparepart dari daftar stok gudang yang tersedia.
2. Daftar bisa dicari (searchable select) supaya tidak perlu scroll.
3. Mekanik mengisi Qty dan klik tombol “Tambah”.
4. Data hasil pilihan tersimpan saat submit progress update dan tampil di ringkasan final.

## Rencana perubahan (Opsi implementasi paling dekat dengan requirement)
- Mengganti bagian `Rekomendasi Part Tambahan` pada dashboard mekanik menjadi:
  - searchable select: “Pilih Suku Cadang” (berisi id/nama + harga)
  - input qty
  - tombol “Tambah”
  - list item terpilih + tombol hapus

## Langkah teknis
1. Tambahkan endpoint (route) JSON untuk mengambil daftar stok gudang.
   - Sumber data: demo dari session `stok_items` (sesuai implementasi `/admin/stok-gudang`).
   - Filter: `stock > 0`.
2. Ubah UI di `resources/views/admin/mekanik/dashboard.blade.php`:
   - ganti input rekomendasi yang ada dengan searchable select
   - buat input qty dan tombol tambah
   - render hasil pilihan ke dalam list
   - simpan hidden input untuk qty + part (agar bisa diproses di backend)
3. Update validasi di `app/Http/Controllers/MechanicServiceController.php@submitProgressUpdate`:
   - terima struktur pilihan sparepart + qty.
4. Update ringkasan di template agar menampilkan daftar sparepart + qty.

## Testing
- Jalankan flow:
  - login mekanik
  - buka dashboard
  - masuk step “Dikerjakan”
  - cari “oli”/nama lain
  - tambah item qty
  - submit progress update
  - cek apakah ringkasan final sesuai.

