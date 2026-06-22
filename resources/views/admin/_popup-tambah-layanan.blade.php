<div id="modalTambahLayanan" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.55); z-index:9999;">
    <div style="max-width:560px; margin:10vh auto; background:#fff; border-radius:16px; padding:18px; border:1px solid rgba(0,0,0,0.1);">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:12px;">
            <div>
                <div style="font-size:14px; font-weight:900; color:#0f172a; margin-bottom:4px;">Tambah Layanan Baru</div>
                <div style="font-size:12px; color:#64748b; font-weight:700; line-height:1.5;">
                    ID <b>L00</b> dilindungi dan tidak boleh dibuat ulang.
                </div>
            </div>
            <button type="button" id="btnCloseModalTambahLayanan" style="padding:8px 10px; background:#f1f5f9; border:1px solid rgba(15,23,42,0.12); border-radius:12px; cursor:pointer; font-weight:900;">✕</button>
        </div>

        <form method="POST" action="/admin/data-layanan/store" autocomplete="off">
            @csrf

            <div style="display:grid; gap:12px;">
                <div style="display:grid; gap:6px">
                    <label style="font-size:12px; color:#64748b; font-weight:800">ID Jasa</label>
                    <input type="text" name="id_jasa" required maxlength="10" style="padding:10px; border:1px solid #e6edf3; border-radius:10px" placeholder="Contoh: L04">
                </div>

                <div style="display:grid; gap:6px">
                    <label style="font-size:12px; color:#64748b; font-weight:800">Nama Jasa Layanan</label>
                    <input type="text" name="nama_jasa" required maxlength="255" style="padding:10px; border:1px solid #e6edf3; border-radius:10px" placeholder="Contoh: Servis AC...">
                </div>

                <div style="display:grid; gap:6px">
                    <label style="font-size:12px; color:#64748b; font-weight:800">Estimasi Harga</label>
                    <input type="number" name="estimasi_harga" required min="0" style="padding:10px; border:1px solid #e6edf3; border-radius:10px" placeholder="Contoh: 150000">
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:16px; flex-wrap:wrap;">
                <button type="button" id="btnCancelModalTambahLayanan" style="padding:10px 14px; background:#f1f5f9; color:#0f172a; border:1px solid rgba(15,23,42,0.12); border-radius:12px; cursor:pointer; font-weight:900;">Batal</button>
                <button type="submit" style="padding:10px 14px; background:#16a34a; color:#fff; border:none; border-radius:12px; cursor:pointer; font-weight:900;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function(){
        const modal = document.getElementById('modalTambahLayanan');
        const btnTambah = document.getElementById('btnTambahLayanan');
        const btnClose = document.getElementById('btnCloseModalTambahLayanan');
        const btnCancel = document.getElementById('btnCancelModalTambahLayanan');

        if (!modal || !btnTambah || !btnClose || !btnCancel) return;

        function openModal(){
            // Pop-up selalu tampil saat tombol menu di-klik
            modal.style.display = 'block';
        }

        // Jika halaman hasil submit menunjukkan flash message,
        // modal langsung ditutup supaya tabel yang di-redirect terasa ter-update.
        @if(session('success') || session('error'))
            closeModal();
        @endif




        function closeModal(){
            modal.style.display = 'none';
        }

        btnTambah.addEventListener('click', openModal);
        btnClose.addEventListener('click', closeModal);
        btnCancel.addEventListener('click', closeModal);

        modal.addEventListener('click', function(e){
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', function(e){
            if (e.key === 'Escape') closeModal();
        });
    })();
</script>

