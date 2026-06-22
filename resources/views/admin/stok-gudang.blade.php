@extends('admin.dashboard-layout')

@section('title','Stok Gudang - Admin')
@section('heading','Stok Gudang')
@section('subheading','Manajemen Inventaris & Stok Gudang')

@section('content')
<style>
    body { background:#f4f4f5; }
    .inventory-header {
        display:flex;
        flex-direction:column;
        gap:10px;
        margin-bottom:20px;
    }

    .inventory-actions {
        display:flex;
        flex-wrap:wrap;
        gap:12px;
        justify-content:space-between;
        align-items:center;
        margin-bottom:18px;
    }

    .inventory-actions .button-row {
        display:flex;
        flex-wrap:wrap;
        gap:10px;
    }

    .btn-secondary, .btn-primary, .btn-outline {
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:10px 14px;
        border-radius:12px;
        border:1px solid transparent;
        font-weight:700;
        text-decoration:none;
        transition:0.2s;
    }

    .btn-primary { background:#dc2626; color:#fff; }
    .btn-primary:hover { opacity:.95; }
    .btn-secondary { background:#f8fafc; color:#1f2937; border-color:rgba(15,23,42,.08); }
    .btn-secondary:hover { background:#e2e8f0; }
    .btn-outline { background:#fff; color:#1f2937; border-color:#d1d5db; }

    .search-box {
        display:flex;
        align-items:center;
        gap:10px;
        flex:1;
        min-width:220px;
    }

    .search-box input {
        width:100%;
        padding:10px 12px;
        border-radius:12px;
        border:1px solid #d1d5db;
        color:#0f172a;
        background:#f8fafc;
    }

    .section-title-small {
        font-size:14px;
        color:#475569;
        margin-bottom:12px;
        font-weight:700;
    }

    .inventory-table, .activity-table {
        width:100%;
        border-collapse:collapse;
        margin-bottom:20px;
    }

    .inventory-table th, .inventory-table td,
    .activity-table th, .activity-table td {
        padding:12px 14px;
        border-bottom:1px solid #e2e8f0;
        font-size:13px;
        color:#1f2937;
        text-align:left;
    }

    .inventory-table th, .activity-table th {
        background:#f8fafc;
        color:#475569;
        font-weight:700;
    }

    .badge-status {
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:6px 10px;
        border-radius:999px;
        font-size:12px;
        font-weight:700;
    }

    .badge-success { background:#dcfce7; color:#166534; }
    .badge-warning { background:#fef3c7; color:#b45309; }
    .badge-danger { background:#fee2e2; color:#b91c1c; }

    .table-wrapper {
        border-radius:18px;
        overflow:hidden;
        border:1px solid #e2e8f0;
        background:#fff;
        box-shadow:0 8px 20px rgba(15,23,42,.05);
    }

    .table-section {
        padding:18px 20px;
    }

    .table-caption {
        font-size:14px;
        color:#475569;
        margin-bottom:14px;
        font-weight:700;
    }

    .footnote {
        font-size:13px;
        color:#475569;
        background:#f8fafc;
        padding:14px 18px;
        border-radius:14px;
        border:1px solid #e2e8f0;
    }

    @media (max-width: 900px) {
        .inventory-actions { flex-direction:column; align-items:flex-start; }
        .search-box { width:100%; }
    }

    /* Button colors for Edit/Delete */
    .btn-edit {
        background: #2563eb !important;
        color: #fff !important;
        border: 1px solid rgba(37,99,235,0.55) !important;
    }
    .btn-edit:hover { opacity: .95; }

    .btn-delete {
        background: #dc2626 !important;
        color: #fff !important;
        border: 1px solid rgba(220,38,38,0.55) !important;
    }
    .btn-delete:hover { opacity: .95; }

    /* Delete modal */
    .danger-gradient { background:linear-gradient(90deg, rgba(220,38,38,0.18), rgba(239,68,68,0.10)); }
</style>

<div class="inventory-header">
    <div style="font-size:18px; font-weight:800; color:#0f172a;">BENGKEL CONNECT [ADMIN]</div>
    <div style="color:#475569; font-size:14px;">Manajemen Inventaris & Stok Gudang</div>
</div>

<div class="inventory-actions">
    <style>
        /* Edit modal trigger */
        #editModalBackdrop{ }
    </style>
        <div class="button-row">
            <a href="#" id="btn-add" class="btn-primary"><i class="bi bi-plus-lg"></i> Tambah Barang Baru</a>

            <form action="/admin/stok-gudang/import" method="POST" enctype="multipart/form-data" style="display:inline-block;">
                @csrf
                <label class="btn-secondary" style="cursor:pointer;">
                    <i class="bi bi-file-earmark-arrow-down"></i>
                    <input type="file" name="import_file" accept="text/csv" style="display:none;" onchange="this.form.submit()">
                    Import Excel
                </label>
            </form>

            <a href="/admin/stok-gudang/export" class="btn-secondary"><i class="bi bi-file-earmark-arrow-up"></i> Export Laporan</a>
        </div>
        <div class="search-box">
            <form action="/admin/stok-gudang" method="GET" style="display:flex; width:100%; gap:10px; align-items:center;">
                <label style="font-weight:700; color:#334155;">Cari Part:</label>
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari nama atau kode part..." />
                <button type="submit" class="btn-outline">Cari</button>
            </form>
        </div>
    </div>

    {{-- Modal Add Item (modern) --}}
<div id="addModalBackdrop" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.55); z-index:2000; padding:20px; align-items:center; justify-content:center;">
        <div id="addModal" style="width:min(720px,100%); background:#fff; border-radius:20px; box-shadow:0 30px 80px rgba(15,23,42,0.25); border:1px solid rgba(15,23,42,0.08); overflow:hidden;">
            <div style="padding:18px 20px; background:linear-gradient(90deg, rgba(220,38,38,0.16), rgba(14,165,233,0.12)); display:flex; align-items:flex-start; justify-content:space-between; gap:16px;">
                <div>
                    <div style="font-size:16px; font-weight:900; color:#0f172a;">Tambah Barang Baru</div>
                    <div style="margin-top:4px; font-size:13px; color:#475569;">Input data suku cadang untuk stok gudang (demo tersimpan di session).</div>
                </div>
                <button type="button" id="addModalClose" class="btn-outline" style="padding:8px 12px; border-radius:12px; border:1px solid #d1d5db; background:#fff; cursor:pointer; font-weight:900;">✕</button>
            </div>

            {{-- Edit Modal (Aktif dari tombol Edit) --}}
            <div id="editModalBackdrop" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.55); z-index:2001; padding:20px; align-items:center; justify-content:center;">
                <div id="editModal" style="width:min(720px,100%); background:#fff; border-radius:20px; box-shadow:0 30px 80px rgba(15,23,42,0.25); border:1px solid rgba(15,23,42,0.08); overflow:hidden;">
                    <div style="padding:18px 20px; background:linear-gradient(90deg, rgba(14,165,233,0.16), rgba(220,38,38,0.12)); display:flex; align-items:flex-start; justify-content:space-between; gap:16px;">
                        <div>
                            <div style="font-size:16px; font-weight:900; color:#0f172a;">Edit Barang</div>
                            <div style="margin-top:4px; font-size:13px; color:#475569;">Ubah data suku cadang (demo tersimpan di session).</div>
                        </div>
                        <button type="button" id="editModalClose" class="btn-outline" style="padding:8px 12px; border-radius:12px; border:1px solid #d1d5db; background:#fff; cursor:pointer; font-weight:900;">✕</button>
                    </div>

                    <div style="padding:18px 20px;">
                        <form action="/admin/stok-gudang/edit" method="POST" id="editItemForm">
                            @csrf

                            <input type="hidden" name="id" id="editItemId">

                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                                <div>
                                    <label style="display:block; font-weight:800; font-size:13px; color:#334155; margin-bottom:6px;">ID Part</label>
                                    <input name="id_display" id="editItemIdDisplay" value="" disabled class="form-control" style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:12px; background:#f8fafc;" />
                                </div>

                                <div>
                                    <label style="display:block; font-weight:800; font-size:13px; color:#334155; margin-bottom:6px;">Kategori</label>
                                    <input name="category" id="editItemCategory" placeholder="ex: Pelumas" class="form-control" style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:12px;" />
                                </div>

                                <div style="grid-column:1/-1;">
                                    <label style="display:block; font-weight:800; font-size:13px; color:#334155; margin-bottom:6px;">Nama Suku Cadang</label>
                                    <input name="name" id="editItemName" placeholder="Nama barang" required class="form-control" style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:12px;" />
                                </div>

                                <div>
                                    <label style="display:block; font-weight:800; font-size:13px; color:#334155; margin-bottom:6px;">Stok</label>
                                    <input name="stock" id="editItemStock" placeholder="Jumlah stok" type="number" required class="form-control" style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:12px;" />
                                </div>

                                <div>
                                    <label style="display:block; font-weight:800; font-size:13px; color:#334155; margin-bottom:6px;">Satuan</label>
                                    <input name="unit" id="editItemUnit" placeholder="ex: pcs / Botol" class="form-control" style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:12px;" />
                                </div>

                                <div style="grid-column:1/-1;">
                                    <label style="display:block; font-weight:800; font-size:13px; color:#334155; margin-bottom:6px;">Harga Jual (angka)</label>
                                    <input name="price" id="editItemPrice" placeholder="angka saja" type="number" required class="form-control" style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:12px;" />
                                </div>
                            </div>

                            <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:16px; flex-wrap:wrap;">
                                <button type="button" id="editModalCancel" class="btn-outline" style="padding:10px 14px; border-radius:12px; border:1px solid #d1d5db; background:#fff; cursor:pointer; font-weight:900;">Batal</button>
                                <button type="submit" class="btn-primary" style="padding:10px 14px; border-radius:12px; cursor:pointer;">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div style="padding:18px 20px;">
                @if(session('success'))
                    <div style="background:#dcfce7; border:1px solid #bbf7d0; color:#166534; padding:10px 12px; border-radius:12px; font-weight:800; margin-bottom:12px;">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="/admin/stok-gudang/add" method="POST" id="addItemForm">
                    @csrf

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div>
                            <label style="display:block; font-weight:800; font-size:13px; color:#334155; margin-bottom:6px;">ID Part</label>
                            <input name="id" value="{{ old('id') }}" placeholder="ex: PRT-006" required class="form-control" style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:12px;" />
                            @error('id')<div style="color:#b91c1c; font-size:12px; font-weight:800; margin-top:6px;">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label style="display:block; font-weight:800; font-size:13px; color:#334155; margin-bottom:6px;">Kategori</label>
                            <input name="category" value="{{ old('category') }}" placeholder="ex: Pelumas" class="form-control" style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:12px;" />
                            @error('category')<div style="color:#b91c1c; font-size:12px; font-weight:800; margin-top:6px;">{{ $message }}</div>@enderror
                        </div>

                        <div style="grid-column:1/-1;">
                            <label style="display:block; font-weight:800; font-size:13px; color:#334155; margin-bottom:6px;">Nama Suku Cadang</label>
                            <input name="name" value="{{ old('name') }}" placeholder="Nama barang" required class="form-control" style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:12px;" />
                            @error('name')<div style="color:#b91c1c; font-size:12px; font-weight:800; margin-top:6px;">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label style="display:block; font-weight:800; font-size:13px; color:#334155; margin-bottom:6px;">Stok</label>
                            <input name="stock" value="{{ old('stock') }}" placeholder="Jumlah stok" type="number" required class="form-control" style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:12px;" />
                            @error('stock')<div style="color:#b91c1c; font-size:12px; font-weight:800; margin-top:6px;">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label style="display:block; font-weight:800; font-size:13px; color:#334155; margin-bottom:6px;">Satuan</label>
                            <input name="unit" value="{{ old('unit') }}" placeholder="ex: pcs / Botol" class="form-control" style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:12px;" />
                            @error('unit')<div style="color:#b91c1c; font-size:12px; font-weight:800; margin-top:6px;">{{ $message }}</div>@enderror
                        </div>

                        <div style="grid-column:1/-1;">
                            <label style="display:block; font-weight:800; font-size:13px; color:#334155; margin-bottom:6px;">Harga Jual (angka)</label>
                            <input name="price" value="{{ old('price') }}" placeholder="angka saja" type="number" required class="form-control" style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:12px;" />
                            @error('price')<div style="color:#b91c1c; font-size:12px; font-weight:800; margin-top:6px;">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:16px; flex-wrap:wrap;">
                        <button type="button" id="addModalCancel" class="btn-outline" style="padding:10px 14px; border-radius:12px; border:1px solid #d1d5db; background:#fff; cursor:pointer; font-weight:900;">Batal</button>
                        <button type="submit" class="btn-primary" style="padding:10px 14px; border-radius:12px; cursor:pointer;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<div class="table-wrapper">
    <div class="table-section">
        <div class="table-caption">📦 STATUS KETERSEDIAAN SUKU CADANG</div>
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>ID Part</th>
                    <th>Nama Suku Cadang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Harga Jual</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                @foreach($items as $it)
                    @php
                        $statusClass = 'badge-success';
                        $statusLabel = '🟢 Aman';
                        if (($it['stock'] ?? 0) <= 3) { $statusClass = 'badge-danger'; $statusLabel = '🔴 KRITIS'; }
                        elseif (($it['stock'] ?? 0) <= 10) { $statusClass = 'badge-warning'; $statusLabel = '🟡 Menipis'; }
                    @endphp
                    <tr>
                        <td>{{ $it['id'] ?? '-' }}</td>
                        <td>{{ $it['name'] ?? '-' }}</td>
                        <td>{{ $it['category'] ?? '-' }}</td>
                        <td>{{ $it['stock'] ?? 0 }}</td>
                        <td>{{ $it['unit'] ?? '-' }}</td>
                        <td>Rp {{ number_format($it['price'] ?? 0,0,',','.') }}</td>
                        <td><span class="badge-status {{ $statusClass }}">{{ $statusLabel }}</span></td>
                        <td>
                            <div style="display:flex; gap:8px; flex-wrap:wrap;">
<a href="#" class="btn-edit" style="padding:8px 10px; border-radius:10px; text-decoration:none;" data-action="edit" data-id="{{ $it['id'] ?? '' }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <button type="button"
                                    class="btn-delete"
                                    style="padding:8px 10px; border-radius:10px; border:1px solid rgba(220,38,38,0.55); background:#dc2626; cursor:pointer; font-weight:800;"
                                    onclick="openDeleteModal('{{ $it['id'] ?? '' }}')">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>

    <div class="table-section">
        <div class="table-caption">📈 AKTIVITAS STOK TERAKHIR (LOG KELUAR/MASUK)</div>
        <table class="activity-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Suku Cadang</th>
                    <th>Perubahan</th>
                    <th>Referensi Transaksi</th>
                    <th>Petugas</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>04 Juni 2026</td>
                    <td>Timbal Balancing</td>
                    <td>-2 pcs</td>
                    <td>Nota: INV/20260604/0142</td>
                    <td>Admin (Auto)</td>
                </tr>
                <tr>
                    <td>04 Juni 2026</td>
                    <td>Pentil Ban Tubeless</td>
                    <td>-1 pcs</td>
                    <td>Nota: INV/20260604/0142</td>
                    <td>Admin (Auto)</td>
                </tr>
                <tr>
                    <td>03 Juni 2026</td>
                    <td>Oli MPX2 0.8 Liter</td>
                    <td>+48 pcs</td>
                    <td>Restock Supplier PT. A</td>
                    <td>Admin (Manual)</td>
                </tr>
            </tbody>
        </table>

        <div class="footnote">*Catatan: Stok otomatis berkurang setiap kali Admin mengonfirmasi pengiriman Nota Tagihan.</div>
    </div>
</div>
 
    <script>
        (function(){
            // Add modal
            const btnAdd = document.getElementById('btn-add');
            const backdropAdd = document.getElementById('addModalBackdrop');
            const modalAddClose = document.getElementById('addModalClose');
            const modalAddCancel = document.getElementById('addModalCancel');

            const openAdd = () => { if(backdropAdd) backdropAdd.style.display = 'flex'; };
            const closeAdd = () => { if(backdropAdd) backdropAdd.style.display = 'none'; };

            if (btnAdd && backdropAdd && modalAddClose && modalAddCancel) {
                btnAdd.addEventListener('click', (e) => { e.preventDefault(); openAdd(); });
                modalAddClose.addEventListener('click', closeAdd);
                modalAddCancel.addEventListener('click', closeAdd);
                backdropAdd.addEventListener('click', (e) => { if (e.target === backdropAdd) closeAdd(); });
                document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeAdd(); });
            }

            // Edit modal
            const editButtons = document.querySelectorAll('[data-action="edit"]');
            const editBackdrop = document.getElementById('editModalBackdrop');
            const editClose = document.getElementById('editModalClose');
            const editCancel = document.getElementById('editModalCancel');

            const idInput = document.getElementById('editItemId');
            const idDisplay = document.getElementById('editItemIdDisplay');
            const formCategory = document.getElementById('editItemCategory');
            const formName = document.getElementById('editItemName');
            const formStock = document.getElementById('editItemStock');
            const formUnit = document.getElementById('editItemUnit');
            const formPrice = document.getElementById('editItemPrice');

            const openEdit = () => { if(editBackdrop) editBackdrop.style.display = 'flex'; };
            const closeEdit = () => { if(editBackdrop) editBackdrop.style.display = 'none'; };

            if (editButtons.length && editBackdrop && editClose && editCancel && idInput) {

                const fillEditForm = (btn) => {
                    // Ambil id dari atribut data-id
                    const id = btn.getAttribute('data-id') || '';
                    idInput.value = id;
                    idDisplay.value = id;

                    // Ambil nilai lain dari row terdekat (td urutan sesuai tabel)
                    const row = btn.closest('tr');
                    if (row) {
                        const tds = row.querySelectorAll('td');
                        // kolom: 0=id,1=name,2=category,3=stock,4=unit,5=price(display Rp ...),6=status,7=aksi
                        const name = (tds[1]?.innerText || '').trim();
                        const category = (tds[2]?.innerText || '').trim();
                        const stock = (tds[3]?.innerText || '').trim();
                        const unit = (tds[4]?.innerText || '').trim();
                        const priceText = (tds[5]?.innerText || '').trim();
                        const priceNum = priceText.replace(/[^0-9]/g, '');

                        if (formName) formName.value = name;
                        if (formCategory) formCategory.value = category;
                        if (formStock) formStock.value = parseInt(stock || '0', 10);
                        if (formUnit) formUnit.value = unit;
                        if (formPrice) formPrice.value = parseInt(priceNum || '0', 10);
                    }
                };

                editButtons.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        fillEditForm(btn);
                        // Pastikan UI menampilkan data yang dipilih
                        if (idDisplay) idDisplay.value = btn.getAttribute('data-id') || '';
                        openEdit();
                    });
                });

                editClose.addEventListener('click', closeEdit);
                editCancel.addEventListener('click', closeEdit);
                editBackdrop.addEventListener('click', (e) => { if (e.target === editBackdrop) closeEdit(); });
                document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeEdit(); });
            }
        })();
    </script>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModalBackdrop" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.55); z-index:3000; padding:20px; align-items:center; justify-content:center;">
        <div id="deleteModal" style="width:min(520px,100%); background:#fff; border-radius:20px; box-shadow:0 30px 80px rgba(15,23,42,0.25); border:1px solid rgba(15,23,42,0.08); overflow:hidden;">
            <div style="padding:18px 20px; background:linear-gradient(90deg, rgba(220,38,38,0.18), rgba(239,68,68,0.10)); display:flex; align-items:flex-start; justify-content:space-between; gap:16px;">
                <div>
                    <div style="font-size:16px; font-weight:900; color:#b91c1c;">Konfirmasi Hapus</div>
                    <div style="margin-top:4px; font-size:13px; color:#475569;">Data suku cadang akan dihapus secara permanen.</div>
                </div>
                <button type="button" id="deleteModalClose" class="btn-outline" style="padding:8px 12px; border-radius:12px; border:1px solid #d1d5db; background:#fff; cursor:pointer; font-weight:900;">✕</button>
            </div>

            <div style="padding:18px 20px;">
                <div id="deleteModalBody" style="font-size:13px; color:#334155; font-weight:800; margin-bottom:14px;"></div>

                <form id="deleteForm" action="/admin/stok-gudang/delete" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="deleteItemId" value="">

                    <div style="display:flex; gap:10px; justify-content:flex-end; flex-wrap:wrap;">
                        <button type="button" id="deleteModalCancel" class="btn-outline" style="padding:10px 14px; border-radius:12px; border:1px solid #d1d5db; background:#fff; cursor:pointer; font-weight:900;">Batal</button>
                        <button type="submit" class="btn-delete" style="padding:10px 14px; border-radius:12px; border:1px solid rgba(220,38,38,0.55); background:#dc2626; cursor:pointer; font-weight:900;">
                            Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function(){
            const deleteModalBackdrop = document.getElementById('deleteModalBackdrop');
            const deleteModalClose = document.getElementById('deleteModalClose');
            const deleteModalCancel = document.getElementById('deleteModalCancel');
            const deleteForm = document.getElementById('deleteForm');
            const deleteItemId = document.getElementById('deleteItemId');
            const deleteModalBody = document.getElementById('deleteModalBody');

            if (!deleteModalBackdrop || !deleteModalClose || !deleteModalCancel || !deleteForm || !deleteItemId || !deleteModalBody) return;

            window.openDeleteModal = function(id){
                deleteItemId.value = id;
                deleteModalBody.textContent = `ID Part: ${id || '-'}`;
                deleteModalBackdrop.style.display = 'flex';
            }

            const closeDelete = () => { deleteModalBackdrop.style.display = 'none'; }

            deleteModalClose.addEventListener('click', closeDelete);
            deleteModalCancel.addEventListener('click', closeDelete);
            deleteModalBackdrop.addEventListener('click', (e) => { if(e.target === deleteModalBackdrop) closeDelete(); });
            document.addEventListener('keydown', (e) => { if(e.key === 'Escape') closeDelete(); });
        })();
    </script>
@endsection
