@extends('admin.dashboard-layout')

@section('title','Kelola Teknisi')

@section('heading','Kelola Teknisi')

@section('subheading','Kelola teknisi, ketersediaan, penugasan & pencarian')

@section('content')
    <style>
        .section-wrap{display:grid; gap:14px;}
        .card-soft{background:#fff; border:1px solid rgba(15,23,42,0.06); border-radius:16px; overflow:hidden;}
        .card-head{padding:14px 16px; background:#f8fafc; border-bottom:1px solid #f1f5f9; font-weight:900; color:#0f172a; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;}
        .btn{display:inline-block; padding:10px 14px; border-radius:12px; text-decoration:none; font-weight:900; border:none; cursor:pointer;}
        .btn-danger{background:#dc2626; color:#fff;}
        .btn-secondary{background:#e2e8f0; color:#475569;}
        .btn-primary{background:#0ea5e9; color:#fff;}
        .btn-success{background:#16a34a; color:#fff;}
        .table{width:100%; border-collapse:collapse;}
        th{padding:12px 14px; text-align:left; font-size:13px; color:#64748b; font-weight:900; border-bottom:1px solid #f1f5f9;}
        td{padding:12px 14px; border-bottom:1px solid #f1f5f9; font-size:13px; color:#0f172a; vertical-align:middle;}
        .badge{padding:8px 12px; border-radius:999px; font-weight:900; font-size:12px; display:inline-block;}
        .badge-available{background:#dcfce7; color:#166534;}
        .badge-busy{background:#fef3c7; color:#92400e;}
        .badge-off{background:#e5e7eb; color:#374151;}
        .badge-suspend{background:#fee2e2; color:#b91c1c;}
        .muted{color:#64748b; font-weight:800; font-size:12px;}
        .field{display:grid; gap:6px;}
        input[type=text], input[type=email], input[type=password], textarea, select{
            width:100%; padding:12px; border:1px solid #cbd5e1; border-radius:10px; font-size:13px; background:#fff;
        }
        .grid-2{display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:12px;}
        .grid-3{display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:12px;}
        .actions-right{display:flex; justify-content:flex-end; gap:10px; flex-wrap:wrap;}
        .profile{display:flex; align-items:center; gap:12px;}
        .avatar{width:44px; height:44px; border-radius:14px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-weight:900; color:#334155; border:1px solid #e2e8f0;}
        .avatar img{width:44px; height:44px; border-radius:14px; object-fit:cover;}
        .subcell{display:grid; gap:4px;}
        .hide{display:none;}
        
        /* Gaya Tambahan untuk Layout Bengkel Connect */
        .brand-header { font-size: 15px; font-weight: 1000; color: #1e3a8a; letter-spacing: 0.5px; }
        .notification-box { background: #f0fdf4; border-left: 4px solid #16a34a; padding: 12px 16px; border-radius: 8px; margin-top: 6px; }
    </style>

    <div class="section-wrap" style="padding:16px 14px;">

        {{-- HEADER PATH SYSTEM --}}
        <div class="brand-header">
            BENGKEL CONNECT &gt; KELOLA TEKNISI & MANAJEMEN PENUGASAN MEKANIK
        </div>

        {{-- TOP BAR ACTIONS --}}
        <div class="card-soft">
            <div class="card-head">
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <button type="button" class="btn btn-danger" onclick="toggleModal('modalTambahTeknisi')">➕ + Tambah Mekanik Baru</button>
                    <button type="button" class="btn btn-secondary" onclick="alert('Mencetak jadwal harian mekanik...')">📋 Cetak Jadwal Harian</button>
                </div>
                <div class="field" style="min-width:280px;">
                    <input id="searchTeknisi" type="text" placeholder="🔍 Cari Nama / Email / ID..." oninput="filterTeknisi()" />
                </div>
            </div>

            {{-- FILTER SYSTEM --}}
            <div style="padding:14px 16px; background: #fafafa; border-bottom: 1px solid #f1f5f9;">
                <div class="grid-3">
                    <div class="field">
                        <span class="muted">Filter Status</span>
                        <select id="filterStatus" onchange="filterTeknisi()">
                            <option value="all">Semua Status</option>
                            <option value="available">🟢 Tersedia (Available)</option>
                            <option value="busy">🛠️ SIBUK (Busy)</option>
                            <option value="off">⚪ Off</option>
                            <option value="suspend">🔴 Suspend</option>
                        </select>
                    </div>
                    <div class="field">
                        <span class="muted">Filter Keahlian Spesialis</span>
                        <input id="filterKeahlian" type="text" placeholder="contoh: Spooring, Kelistrikan" oninput="filterTeknisi()" />
                    </div>
                    <div class="field">
                        <span class="muted">Filter Wilayah Kerja</span>
                        <input id="filterArea" type="text" placeholder="contoh: Perum Asri, Jakarta" oninput="filterTeknisi()" />
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL 1: STATUS & KETERSEDIAAN MEKANIK (REAL-TIME) --}}
        <div class="card-soft">
            <div class="card-head">
                <div style="display:flex; align-items:center; gap:8px;">
                    <span>📊 STATUS & KETERSEDIAAN MEKANIK (REAL-TIME)</span>
                </div>
                <span class="muted">Sinkronisasi Otomatis</span>
            </div>
            
            <div style="overflow:auto;">
                <table class="table" id="tblTeknisi">
                    <thead>
                        <tr>
                            <th style="width:120px;">ID</th>
                            <th style="min-width:240px;">Nama Mekanik</th>
                            <th style="min-width:240px;">Keahlian Spesialis</th>
                            <th style="min-width:200px;">Status Kerja</th>
                            <th style="min-width:200px; text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $mekaniksCollection = collect($mekaniks ?? []);
                            $activeStatuses = ['dikirim_ke_mekanik','sedang_dikerjakan'];
                        @endphp

                        @forelse($mekaniksCollection as $mekanik)
                            @php
                                $activeCount = 0;
                                if(isset($bookings) && $bookings){
                                    try{
                                        $activeCount = collect($bookings)
                                            ->where('mekanik_id', $mekanik->id)
                                            ->whereIn('status', $activeStatuses)
                                            ->count();
                                    } catch(\Throwable $e) { $activeCount = 0; }
                                }
                                
                                // Kondisi status dari ilustrasi Bengkel Connect
                                $statusKey = $activeCount > 0 ? 'busy' : 'available';
                                $statusText = $activeCount > 0 ? '🛠️ SIBUK' : '🟢 Tersedia';
                                $badgeClass = $activeCount > 0 ? 'badge-busy' : 'badge-available';

                                $keahlianText = $mekanik->keahlian ?? $mekanik->specialization ?? '-';
                                $areaText = $mekanik->area ?? $mekanik->wilayah ?? '-';
                            @endphp

                            <tr data-id="{{ $mekanik->id }}" 
                                data-name="{{ strtolower($mekanik->name ?? '') }}" 
                                data-status="{{ $statusKey }}" 
                                data-keahlian="{{ strtolower($keahlianText) }}" 
                                data-area="{{ strtolower($areaText) }}">
                                <td>
                                    <div style="font-weight:1000; color:#1e40af;">
                                        {{ 'MK-' . str_pad((string)$mekanik->id, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="profile">
                                        <div class="avatar">{{ strtoupper(substr($mekanik->name ?? '-',0,1)) }}</div>
                                        <div class="subcell">
                                            <div style="font-weight:1000;">{{ $mekanik->name ?? '-' }}</div>
                                            <div class="muted">{{ $mekanik->no_hp ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight:1000;">{{ $keahlianText }}</div>
                                    <div class="muted">Area: {{ $areaText }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                    <span class="muted" style="margin-left:6px;">({{ $activeCount }} Tugas Active)</span>
                                </td>
                                <td style="text-align:right;">
                                    <button type="button" class="btn btn-secondary" onclick="alert('Membuka Panel Kontrol Tugas & Shift untuk {{ $mekanik->name }}')">⚙️ Atur Tugas / Shift</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding:24px; text-align:center;">
                                    <div class="muted">Belum ada mekanik terdaftar dalam sistem Bengkel Connect.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TABEL 2: WORKLOAD / BEBAN KERJA AKTIF DI LAPANGAN --}}
        <div class="card-soft">
            <div class="card-head">
                <div style="display:flex; align-items:center; gap:8px;">
                    <span>📋 WORKLOAD / BEBAN KERJA AKTIF DI LAPANGAN</span>
                </div>
            </div>
            <div style="overflow:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="min-width:220px;">Nama Mekanik</th>
                            <th style="min-width:180px;">Unit Kendaraan</th>
                            <th style="min-width:160px;">Tipe Layanan</th>
                            <th style="min-width:240px;">Lokasi Kerja / Alamat</th>
                            <th style="min-width:180px;">Progres Kerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mekaniksCollection as $mekanik)
                            @php
                                // Mengambil data booking aktif pertama milik mekanik untuk workload lapangan
                                $currentBooking = null;
                                if(isset($bookings) && $bookings){
                                    $currentBooking = collect($bookings)
                                        ->where('mekanik_id', $mekanik->id)
                                        ->whereIn('status', $activeStatuses)
                                        ->first();
                                }
                            @endphp
                            <tr>
                                <td><div style="font-weight:1000;">{{ $mekanik->name ?? '-' }}</div></td>
                                <td>{{ $currentBooking->kendaraan ?? ($currentBooking ? 'Unit Kendaraan' : '-') }}</td>
                                <td>
                                    @if($currentBooking)
                                        🏠 <span>Home Serv.</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($currentBooking)
                                        {{ $currentBooking->alamat ?? 'Alamat Pelanggan' }}
                                    @else
                                        <span class="muted" style="font-style:italic;">[ Standby / Istirahat ]</span>
                                    @endif
                                </td>
                                <td>
                                    @if($currentBooking)
                                        <b style="color:#d97706;">🚚 OTW ke Lokasi</b>
                                    @else
                                        <span class="muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding:16px; text-align:center;" class="muted">Tidak ada beban kerja lapangan saat ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- NOTIFIKASI SISTEM TERAKHIR --}}
        <div class="notification-box">
            <div style="font-weight:900; color:#15803d; font-size:13px;">📢 NOTIFIKASI SISTEM TERAKHIR:</div>
            <div class="muted" style="color:#1e6b3e; margin-top:4px;">
                [06-06-2026 13:05] Berhasil mengirim penugasan Home Service #BC-0142 ke aplikasi Mekanik.
            </div>
        </div>

    </div>

    {{-- MODAL POP-UP TAMBAH TEKNISI (FORM STRUKTUR GRID HORIZONTAL) --}}
    <div id="modalTambahTeknisi" class="hide" style="position:fixed; inset:0; background:rgba(15,23,42,0.4); z-index:9999; align-items:center; justify-content:center; padding:20px;">
        <div class="card-soft" style="max-width:1050px; width:100%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.15);">
            <div class="card-head" style="background:#1e293b; color:#fff;">
                <span style="font-size:15px; font-weight:1000; color:#fff;">➕ FORMULIR: TAMBAH MEKANIK BARU</span>
                <button class="btn btn-secondary" type="button" style="padding:6px 12px; background:#475569; color:#fff;" onclick="toggleModal('modalTambahTeknisi')">✕ Batal / Tutup</button>
            </div>
            
            <div style="padding:20px;">
                <form method="POST" action="/admin/teknisi/tambah" enctype="multipart/form-data">
                    @csrf
                    <div class="grid-3">
                        <div class="field">
                            <span class="muted">Nama Lengkap Mekanik</span>
                            <input type="text" name="name" required placeholder="Contoh: Agus Saputra" />
                        </div>
                        <div class="field">
                            <span class="muted">Email (opsional)</span>
                            <input type="email" name="email" placeholder="mekanik@example.com" />
                        </div>
                        <div class="field">
                            <span class="muted">No HP (opsional)</span>
                            <input type="text" name="no_hp" placeholder="08xxxxxxxxxx" />
                        </div>
                    </div>

                    <div class="grid-3" style="margin-top:14px;">
                        <div class="field">
                            <span class="muted">Keahlian Spesialis</span>
                            <input type="text" name="keahlian" placeholder="Spooring, Kelistrikan, AC, Oli" />
                        </div>
                        <div class="field">
                            <span class="muted">Password Akun</span>
                            <input type="password" name="password" required placeholder="Min 8 karakter" />
                        </div>
                        <div class="field">
                            <span class="muted">Wilayah / Area Cakupan Kerja</span>
                            <input type="text" name="area" placeholder="Contoh: Perum Asri No. 5" />
                        </div>
                    </div>

                    <div class="grid-2" style="margin-top:14px;">
                        <div class="field">
                            <span class="muted">Dokumen Pendukung Mekanik (KTP/Sertifikat)</span>
                            <input type="file" name="dokumen" accept="image/*,application/pdf" style="padding:8px;" />
                        </div>
                    </div>

                    <div class="actions-right" style="margin-top:20px; border-top:1px solid #e2e8f0; padding-top:14px;">
                        <button type="submit" class="btn btn-success" style="padding:12px 24px; background:#16a34a;">✔ Simpan & Daftarkan Mekanik</button>
                    </div>
                </form>

                <div class="muted" style="margin-top:12px; background:#f8fafc; padding:10px; border-radius:8px; border:1px dashed #cbd5e1;">
                    💡 <b>Catatan Sistem:</b> Mekanik baru yang ditambahkan di atas akan langsung aktif secara real-time dan muncul otomatis di opsi "Pilih Mekanik" pada modul detail pelayanan kendaraan.
                </div>
            </div>
        </div>
    </div>

    <script>
        // Failsafe state kontrol modal popup formulir
        (function(){
            const el = document.getElementById('modalTambahTeknisi');
            if(el) el.classList.add('hide');
        })();

        function toggleModal(id){
            const el = document.getElementById(id);
            if(!el) return;
            if(el.classList.contains('hide')) {
                el.style.display = 'flex';
                el.classList.remove('hide');
            } else {
                el.style.display = 'none';
                el.classList.add('hide');
            }
        }

        function filterTeknisi(){
            const q = (document.getElementById('searchTeknisi')?.value || '').toLowerCase().trim();
            const st = document.getElementById('filterStatus')?.value || 'all';
            const keahlian = (document.getElementById('filterKeahlian')?.value || '').toLowerCase().trim();
            const area = (document.getElementById('filterArea')?.value || '').toLowerCase().trim();

            const rows = document.querySelectorAll('#tblTeknisi tbody tr');
            rows.forEach(r=>{
                if(r.cells.length <= 1) return; // Skip baris kosong/empty state
                
                const name = (r.dataset.name || '');
                const id = (r.dataset.id || '');
                const status = (r.dataset.status || '');
                const k = (r.dataset.keahlian || '');
                const a = (r.dataset.area || '');

                const matchQ = !q || name.includes(q) || id.includes(q);
                const matchSt = st === 'all' || status === st;
                const matchK = !keahlian || k.includes(keahlian);
                const matchA = !area || a.includes(area);

                r.style.display = (matchQ && matchSt && matchK && matchA) ? '' : 'none';
            });
        }
    </script>
@endsection