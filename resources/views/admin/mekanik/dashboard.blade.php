@extends('mekanik.dashboard-layout')

@section('title', 'Dashboard Utama Mekanik')

@section('styles')
    <style>
        .grid-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 18px;
        }
        .card-task {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.06);
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 8px 20px rgba(15,23,42,0.04);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .badge { padding: 6px 10px; border-radius: 999px; font-weight: 700; font-size: 12px; }
        .badge-antrean { background: #fef3c7; color: #92400e; }
        .badge-diagnosa { background: #fef3c7; color: #92400e; }
        .badge-dikerjakan { background: #fbfdff; color: #0369a1; }
        .badge-testdrive { background: #f97316; color: #ffffff; }
        .badge-selesai { background: #16a34a; color: #ffffff; }

        .stepper { display: flex; gap: 10px; flex-wrap: wrap; }
        .step { display: inline-flex; align-items: center; gap: 8px; padding: 10px 12px; border-radius: 12px; background: #fff; border: 1px solid #e6eef6; cursor: pointer; }
        .step.active { background: #eff6ff; border-color: #0ea5e9; }
        .step.done { background: #dcfce7; border-color: #16a34a; }
        .detail-panel { display: none; background: #fbfeff; border: 1px solid #e6f5ff; border-radius: 12px; padding: 14px; }
        .detail-footer { position: sticky; bottom: 0; background: linear-gradient(180deg, rgba(248,250,252,0), #fbfeff 60%); padding-top: 12px; }
        .button-primary { background: #0ea5e9; color: #fff; border: none; padding: 10px 12px; border-radius: 10px; cursor: pointer; font-weight: 700; }
        .button-success { background: #16a34a; color: #fff; border: none; padding: 10px 12px; border-radius: 10px; cursor: pointer; font-weight: 700; }
        input[type=text], textarea, input[type=number] { width: 100%; padding: 10px; border: 1px solid #e6edf3; border-radius: 8px; box-sizing: border-box; }
    </style>
@endsection

@section('content')
    @if(session('success'))
        <div style="background:#dcfce7; border:1px solid #bbf7d0; color:#166534; padding:12px; border-radius:10px; margin-bottom:12px; font-weight:700;">
            {{ session('success') }}
        </div>
    @endif

    @php
        $bookingsCollection = collect($bookings ?? []);
        $countSemua = $bookingsCollection->count();
        $countAntrean = $bookingsCollection->where('status', 'dikirim_ke_mekanik')->count();
        $countDikerjakan = $bookingsCollection->where('status', 'sedang_dikerjakan')->count();
        $countSelesai = $bookingsCollection->where('status', 'selesai')->count();
    @endphp

    <div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:16px">
        <div style="background:#fff; border:1px solid rgba(15,23,42,0.06); padding:12px 16px; border-radius:12px; min-width:120px">
            <div style="font-size:12px; color:#64748b">Semua</div>
            <div style="font-weight:800; font-size:20px">{{ $countSemua }}</div>
        </div>
        <div style="background:#fef3c7; padding:12px 16px; border-radius:12px; min-width:120px">
            <div style="font-size:12px; color:#92400e">Antrean</div>
            <div style="font-weight:800; font-size:20px">{{ $countAntrean }}</div>
        </div>
        <div style="background:#dbf4ff; padding:12px 16px; border-radius:12px; min-width:120px">
            <div style="font-size:12px; color:#0369a1">Dikerjakan</div>
            <div style="font-weight:800; font-size:20px">{{ $countDikerjakan }}</div>
        </div>
        <div style="background:#dcfce7; padding:12px 16px; border-radius:12px; min-width:120px">
            <div style="font-size:12px; color:#166534">Selesai</div>
            <div style="font-weight:800; font-size:20px">{{ $countSelesai }}</div>
        </div>
    </div>

    <div class="grid-cards">
        @forelse($bookings ?? [] as $booking)
            @php
                $status = $booking->status ?? 'dikirim_ke_mekanik';
                $progress = $booking->latest_progress ?? 0;
                $statusLabel = ['dikirim_ke_mekanik'=>'Antrean','sedang_dikerjakan'=>'Dikerjakan','selesai'=>'Selesai'][$status] ?? ucfirst($status);
                $statusClass = ['dikirim_ke_mekanik'=>'badge-antrean','sedang_dikerjakan'=>'badge-dikerjakan','selesai'=>'badge-selesai'][$status] ?? 'badge-antrean';
                
                // Decode JSON aman untuk recommended_parts
                $recommendedParts = [];
                if (!empty($booking->recommended_parts)) {
                    try {
                        $recommendedParts = is_array($booking->recommended_parts) 
                            ? $booking->recommended_parts 
                            : (json_decode($booking->recommended_parts, true) ?: []);
                    } catch (\Exception $e) {
                        $recommendedParts = [];
                    }
                }
            @endphp

            <div class="card-task">
                <div style="display:flex; justify-content:space-between; align-items:flex-start">
                    <div>
                        <div style="font-size:13px; color:#64748b">Nomor Polisi</div>
                        <div style="font-weight:800; font-size:20px">{{ $booking->nopol ?? '-' }}</div>
                        <div style="color:#475569; margin-top:6px">{{ $booking->kendaraan ?? 'Motor' }}</div>
                        <div style="color:#475569; margin-top:4px">Pemilik: {{ $booking->nama ?? '-' }}</div>
                    </div>
                    <div style="text-align:right">
                        <div><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></div>
                        <div style="margin-top:8px; color:#64748b">Progress: {{ $progress }}%</div>
                    </div>
                </div>

                <div style="display:flex; gap:10px; align-items:center; justify-content:space-between; margin-top:6px">
                    @if($status === 'dikirim_ke_mekanik')
                        <form method="POST" action="{{ route('mekanik.update-status', $booking->kode_booking) }}" style="margin:0">
                            @csrf
                            <input type="hidden" name="new_status" value="sedang_dikerjakan">
                            <button type="submit" class="button-primary">Mulai Kerjakan</button>
                        </form>
                    @elseif($status === 'sedang_dikerjakan')
                        <button type="button" class="button-primary" onclick="openWorkStep('{{ $booking->kode_booking }}','dikerjakan')">Update Progres</button>
                    @elseif($status === 'selesai')
                        <button type="button" class="button-success" onclick="openWorkStep('{{ $booking->kode_booking }}','selesai')">Lihat Detail</button>
                        <div style="font-size:12px;color:#64748b;margin-top:6px;font-weight:700">Setelah ini, admin akan mengelola di Laporan Mekanik (Pekerjaan Selesai)</div>

                    @else
                        <button type="button" class="button-success" onclick="openWorkStep('{{ $booking->kode_booking }}','test_drive')">Tes Drive</button>
                    @endif

                    <div style="font-size:12px; color:#94a3b8">Layanan: {{ $booking->layanan ?? '-' }}</div>
                </div>

                <div id="detail-{{ $booking->kode_booking }}" class="detail-panel" data-status="{{ $status }}">
                    <div class="stepper">
                        {{-- 1 Diagnosa --}}
                        <button type="button" class="step" onclick="openWorkStep('{{ $booking->kode_booking }}','diagnosis')"
                                style="background:{{ $status === 'dikirim_ke_mekanik' ? '#fef3c7' : '#fde68a' }}; border-color:{{ $status === 'dikirim_ke_mekanik' ? '#f59e0b' : '#b45309' }}; color:#92400e;">
                            <span>1</span><span>Diagnosa</span>
                        </button>

                        {{-- 2 Dikerjakan --}}
                        <button type="button" class="step" onclick="openWorkStep('{{ $booking->kode_booking }}','dikerjakan')"
                                style="background:{{ $status === 'sedang_dikerjakan' ? '#dbf4ff' : '#dbeafe' }}; border-color:{{ $status === 'sedang_dikerjakan' ? '#0284c7' : '#1d4ed8' }}; color:#0369a1;">
                            <span>2</span><span>Dikerjakan</span>
                        </button>

                        {{-- 3 Test Drive --}}
                        <button type="button" class="step" onclick="openWorkStep('{{ $booking->kode_booking }}','test_drive')"
                                style="background:{{ $status === 'selesai' ? '#fdba74' : '#fff' }}; border-color:#f97316; color:#7c2d12;">
                            <span>3</span><span>Test Drive</span>
                        </button>

                        {{-- 4 Selesai --}}
                        <button type="button" class="step" onclick="openWorkStep('{{ $booking->kode_booking }}','selesai')"
                                style="background:{{ $status === 'selesai' ? '#16a34a' : '#fff' }}; border-color:{{ $status === 'selesai' ? '#16a34a' : '#e6eef6' }}; color:{{ $status === 'selesai' ? '#ffffff' : '#334155' }};">
                            <span>4</span><span>Selesai</span>
                        </button>
                    </div>

                    <div style="margin-top:12px">
                        <div style="margin-bottom:8px"><strong>Keluhan:</strong> {{ $booking->catatan ?? '-' }}</div>
                        
                        <div style="display:grid; gap:10px">
                            <label style="font-weight: 700; font-size: 13px;">Pilih Suku Cadang</label>

                            <div style="display:grid; grid-template-columns: 1fr 120px; gap:10px; align-items:end">
                                <div>
                                    <div style="font-size:12px; color:#94a3b8; font-weight:800; margin-bottom:6px">Ketik nama/Kode (misal: oli)</div>
                                    <input
                                        type="text"
                                        class="form-control"
                                        style="width:100%; padding:10px 12px; border:1px solid #e6edf3; border-radius:10px;"
                                        id="part-search-{{ $booking->kode_booking }}"
                                        placeholder="Pilih Suku Cadang"
                                        oninput="filterParts('{{ $booking->kode_booking }}')"
                                        @if($status !== 'sedang_dikerjakan') disabled @endif
                                    />

                                    <select
                                        id="part-select-{{ $booking->kode_booking }}"
                                        name="part_id"
                                        form="progress-form-{{ $booking->kode_booking }}"
                                        style="width:100%; padding:10px 12px; border:1px solid #e6edf3; border-radius:10px; margin-top:8px; background:#fff;"
                                        @if($status !== 'sedang_dikerjakan') disabled @endif
                                        onchange="onPartSelected('{{ $booking->kode_booking }}')">
                                        <option value="">-- Pilih dari daftar --</option>
                                    </select>

                                    <div style="font-size:12px; color:#64748b; margin-top:6px">
                                        Harga: Rp <span id="part-price-{{ $booking->kode_booking }}">0</span>
                                    </div>
                                </div>

                                <div>
                                    <div style="font-size:12px; color:#94a3b8; font-weight:800; margin-bottom:6px">Qty</div>
                                    <input type="number" min="1" step="1" id="part-qty-{{ $booking->kode_booking }}" value="1"
                                        style="width:100%; padding:10px 12px; border:1px solid #e6edf3; border-radius:10px;" 
                                        @if($status !== 'sedang_dikerjakan') disabled @endif
                                    />
                                </div>
                            </div>

                            <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; justify-content:space-between">
                                <button
                                    type="button"
                                    class="button-primary"
                                    style="width:max-content; font-size: 12px; padding: 6px 10px;"
                                    onclick="addPartToList('{{ $booking->kode_booking }}')"
                                    @if($status !== 'sedang_dikerjakan') disabled @endif
                                >+ Tambah</button>

                                <div style="font-size:12px; color:#94a3b8; font-weight:800">*Daftar stok demo diambil dari session stok gudang (untuk sekarang).</div>
                            </div>

                            <div id="recommended-list-{{ $booking->kode_booking }}" style="display:grid; gap:8px; margin-top:8px">
                                @foreach($recommendedParts as $p)
                                    <div style="display:flex; gap:8px; align-items:center">
                                        <input type="text" value="{{ $p }}" readonly style="flex:1" />
                                        <input type="hidden" name="recommended_parts[]" value="{{ $p }}" form="progress-form-{{ $booking->kode_booking }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>


                    @if($status === 'sedang_dikerjakan')
                                    <div id="progress-panel-{{ $booking->kode_booking }}" style="display:none; margin-top:12px; background:#fff; border:1px solid #e6f5ff; padding:12px; border-radius:10px">
                            <form id="progress-form-{{ $booking->kode_booking }}" method="POST" action="{{ route('mekanik.progress-update', $booking->kode_booking) }}">
                                @csrf
                                <div style="display:grid; gap:10px">
                                    <label>Detail Aktivitas Perbaikan</label>
                                    <textarea name="update_text" rows="3" placeholder="Contoh: Sedang mengganti kampas rem depan baru..."></textarea>
                                    
                                    <label>Persentase Progres (%)</label>
                                    <input type="number" name="progress_percentage" min="0" max="100" value="{{ $progress }}">
                                    
                                    @foreach($recommendedParts as $p)
                                        <input type="hidden" name="recommended_parts[]" value="{{ $p }}">
                                    @endforeach
                                    
                                    <div style="display:flex; gap:8px; justify-content:flex-end; margin-top: 6px;">
                                        <button type="submit" class="button-primary">Kirim Pembaruan</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="final-summary-panel-{{ $booking->kode_booking }}" style="display:none; margin-top:12px; background:#ffffff; border:1px solid #e2e8f0; padding:16px; border-radius:14px">
                            <form method="POST" action="{{ route('mekanik.upload-bukti-kerja', $booking->kode_booking) }}" enctype="multipart/form-data">
                                @csrf
                                <div style="display:grid; gap:14px">
                                    <div style="font-size:14px; font-weight:800; color:#0f172a">RINGKASAN FINAL PENGERJAAN</div>
                                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:14px; color:#0f172a">
                                        <div style="font-weight:700; margin-bottom:4px">Jenis Layanan Utama:</div>
                                        <div style="margin-bottom:10px">{{ $booking->layanan ?? '-' }}</div>
                                        <div style="font-weight:700; margin-bottom:4px">Sparepart Tambahan Terdaftar:</div>
                                        <div style="margin-left:4px; color:#334155">
                                            @forelse($recommendedParts as $p)
                                                <div style="font-size: 13px;">• {{ $p }}</div>
                                            @empty
                                                <div style="font-size: 13px; color: #94a3b8;">- Tidak ada sparepart tambahan</div>
                                            @endforelse
                                        </div>
                                    </div>


                                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:14px; color:#0f172a">
                                        <div style="font-weight:700; margin-bottom:8px">CHECKLIST QUALITY CONTROL</div>
                                        <div style="display:grid; gap:6px; color:#334155">
                                            <label><input type="checkbox" name="qc_check[]" value="pengecekan_fungsi" checked> Pengecekan Fungsi Utama: Semua sistem utama yang diperbaiki berfungsi dengan normal dan aman.</label>
                                            <label><input type="checkbox" name="qc_check[]" value="keamanan_komponen" checked> Keamanan Komponen: Seluruh baut pengikat, komponen, dan part terkait telah terpasang kencang sesuai standar.</label>
                                            <label><input type="checkbox" name="qc_check[]" value="kebersihan_estetika" checked> Kebersihan &amp; Estetika: Area kerja dan kendaraan bersih dari sisa oli, kotoran, atau bekas pengerjaan.</label>
                                        </div>
                                    </div>


                                    <div>
                                        <div style="font-weight:700; margin-bottom:8px">CATATAN & SARAN UNTUK PELANGGAN</div>
                                        <textarea name="customer_recommendation" rows="3" placeholder="Saran pengerjaan berkala atau rekomendasi servis selanjutnya...">{{ $booking->mechanic_note ?? '' }}</textarea>
                                    </div>

                                    <div style="background:#f8fafc; border:1px dashed #cbd5e1; border-radius:12px; padding:14px; color:#334155">
                                        <div style="font-weight:700; margin-bottom:8px">LAMPIRAN FOTO BUKTI FISIK SERVIS</div>
                                        <div style="display:flex; gap:10px; align-items:center;">
                                            <input type="file" name="bukti_pengerjaan" accept="image/*" required>
                                        </div>
                                        <div style="font-size:12px; color:#64748b; margin-top:6px">Unggah foto hasil akhir pengerjaan mekanik sebagai lampiran invoice sistem.</div>
                                    </div>

                                    <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:6px">
                                        <button type="button" class="button-primary" style="background:#64748b" onclick="resetFinalSummary('{{ $booking->kode_booking }}')">Reset</button>
                                        <button type="submit" class="button-success">SELESAIKAN & KIRIM KE ADMIN</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @else
                        <div id="final-summary-panel-{{ $booking->kode_booking }}" style="display:none; margin-top:12px; background:#ffffff; border:1px solid #e2e8f0; padding:16px; border-radius:14px">
                            <div style="font-size:14px; font-weight:800; color:#16a34a; margin-bottom: 8px;">DATA RIWAYAT PENGERJAAN SELESAI</div>
                            @if(!empty($booking->bukti_pengerjaan_path))
                                <div style="margin-bottom:10px">
                                    <img src="{{ asset('storage/' . $booking->bukti_pengerjaan_path) }}" alt="Bukti Pengerjaan" style="max-width:100%; max-height: 250px; border-radius:10px; border:1px solid #cbd5e1" />
                                </div>
                            @endif
                            <div style="font-size:13px; color:#475569">
                                <strong>Catatan Mekanik Lama:</strong> {{ $booking->mechanic_note ?? 'Tidak ada catatan khusus.' }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div style="grid-column:1/-1; padding:20px; background:#fff; border:1px solid rgba(15,23,42,0.04); border-radius:10px; text-align: center; color: #64748b;">
                Belum ada data antrean booking kendaraan yang diarahkan ke mekanik Anda.
            </div>
        @endforelse
    </div>

    <script>
/**
 * Implementasi baru (tanpa library) untuk requirement:
 * - Mekanik memilih dari daftar stok gudang (demo)
 * - Search via input mengetik 2-3 huruf (filter lokal)
 * - Isi qty, klik Tambah
 *
 * Catatan:
 * - Saat ini daftar part diisi via JS dari data demo yang disuntik dari backend (route stok-gudang memakai session).
 * - Karena belum ada endpoint JSON stok-gudang, implementasi ini masih mode “demo lokal” sampai kita tambahkan endpoint JSON.
 */
        const PARTS_DEMO = [
            { id: 'PRT-001', name: 'Oli MPX2 0.8 Liter', price: 65000, stock: 24 },
            { id: 'PRT-002', name: 'Kampas Rem Depan Vario', price: 75000, stock: 8 },
            { id: 'PRT-003', name: 'Timbal Balancing', price: 15000, stock: 45 },
            { id: 'PRT-004', name: 'Pentil Ban Tubeless', price: 10000, stock: 3 },
            { id: 'PRT-005', name: 'Aki GS Astra YTZ6V', price: 285000, stock: 12 },
        ];

        function ensurePartsLoaded(kodeBooking) {
            const sel = document.getElementById('part-select-' + kodeBooking);
            if (!sel) return;
            if (sel.dataset.loaded === '1') return;

            // Hanya stok > 0
            const ready = PARTS_DEMO.filter(p => (p.stock ?? 0) > 0);

            sel.innerHTML = `<option value="">-- Pilih dari daftar --</option>`;
            ready.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = `${p.name} (Rp ${Number(p.price || 0).toLocaleString('id-ID')}, stok ${p.stock})`;
                sel.appendChild(opt);
            });

            sel.dataset.loaded = '1';
        }

        function filterParts(kodeBooking) {
            ensurePartsLoaded(kodeBooking);
            const q = (document.getElementById('part-search-' + kodeBooking)?.value || '').toLowerCase().trim();
            const sel = document.getElementById('part-select-' + kodeBooking);
            if (!sel) return;

            const ready = PARTS_DEMO.filter(p => (p.stock ?? 0) > 0);
            const filtered = !q
                ? ready
                : ready.filter(p => (p.name + ' ' + p.id).toLowerCase().includes(q));

            sel.innerHTML = `<option value="">-- Pilih dari daftar --</option>`;
            filtered.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = `${p.name} (Rp ${Number(p.price || 0).toLocaleString('id-ID')}, stok ${p.stock})`;
                sel.appendChild(opt);
            });
        }

        function onPartSelected(kodeBooking) {
            const sel = document.getElementById('part-select-' + kodeBooking);
            const priceEl = document.getElementById('part-price-' + kodeBooking);
            if (!sel || !priceEl) return;

            const id = sel.value;
            const part = PARTS_DEMO.find(p => p.id === id);
            priceEl.textContent = part ? String(part.price || 0) : '0';
        }

        function addPartToList(kodeBooking) {
            const list = document.getElementById('recommended-list-' + kodeBooking);
            const form = document.getElementById('progress-form-' + kodeBooking);
            if (!list || !form) return;

            const sel = document.getElementById('part-select-' + kodeBooking);
            const qtyEl = document.getElementById('part-qty-' + kodeBooking);
            if (!sel || !qtyEl) return;

            const partId = sel.value;
            if (!partId) return;

            const qty = parseInt(qtyEl.value || '1', 10);
            if (!Number.isFinite(qty) || qty < 1) return;

            const part = PARTS_DEMO.find(p => p.id === partId);
            if (!part) return;

            const display = `${part.name} x${qty}`;


        function openWorkStep(kodeBooking, step) {
            const detail = document.getElementById('detail-' + kodeBooking);
            const progress = document.getElementById('progress-panel-' + kodeBooking);
            const finalSummary = document.getElementById('final-summary-panel-' + kodeBooking);
            const status = detail ? detail.dataset.status : null;
            
            if (detail) detail.style.display = 'block';
            if (progress) progress.style.display = 'none';
            if (finalSummary) finalSummary.style.display = 'none';
            
            if ((step === 'dikerjakan' || step === 'diagnosis' || step === 'test_drive') && status === 'sedang_dikerjakan' && progress) {
                progress.style.display = 'block';
                progress.scrollIntoView({behavior:'smooth', block:'center'});
                return;
            }
            if (step === 'selesai' && finalSummary) {
                finalSummary.style.display = 'block';
                finalSummary.scrollIntoView({behavior:'smooth', block:'center'});
                return;
            }
        }

        function resetFinalSummary(kodeBooking) {
            const panel = document.getElementById('final-summary-panel-' + kodeBooking);
            if (!panel) return;
            const inputs = panel.querySelectorAll('input[type=file], textarea');
            inputs.forEach(el => {
                if (el.type === 'file') el.value = '';
                if (el.tagName.toLowerCase() === 'textarea') el.value = '';
            });
            panel.scrollIntoView({behavior:'smooth', block:'center'});
        }
    </script>
@endsection