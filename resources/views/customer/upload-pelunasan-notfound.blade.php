<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Bukti Pembayaran - Bengkel Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-200 text-slate-900 min-h-screen p-4 antialiased">

    @php
        // Manfaat variabel Laravel untuk integrasi database Anda
        $kodeBooking = $booking->kode_booking ?? 'BC-20260616-4E1IUD';
        $totalBayar = $booking->total_biaya_perbaikan ?? 150000;
    @endphp

    <div class="max-w-xl mx-auto mt-6">
        
        <a href="{{ route('customer.repair-invoice', ['kodeBooking' => $kodeBooking]) }}" class="inline-flex items-center gap-2 px-3 py-2 text-xs font-bold bg-slate-300 text-slate-700 rounded-xl border border-slate-400/30 hover:bg-slate-400/50 hover:text-slate-900 transition-all mb-4">
            ← Kembali ke Invoice
        </a>

        <div class="bg-slate-50 rounded-2xl shadow-xl border border-slate-300 overflow-hidden p-6">
            
            <div class="text-center pb-5 mb-5 border-b border-slate-200">
                <h2 class="text-lg font-extrabold text-slate-800 tracking-wide uppercase">Konfirmasi Bukti Pembayaran</h2>
                <p class="text-xs text-slate-500 mt-1">Silakan unggah foto struk transfer atau tangkapan layar (screenshot) hasil scan QRIS Anda.</p>
            </div>

            <div class="bg-slate-100 rounded-xl p-4 border border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
                <div>
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kode Booking</span>
                    <strong class="text-sm font-extrabold text-slate-800 bg-slate-200/60 px-2 py-1 rounded-md">{{ $kodeBooking }}</strong>
                </div>
                <div class="sm:text-right">
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Nominal Pelunasan</span>
                    <strong class="text-base font-black text-emerald-600">Rp {{ number_format($totalBayar, 0, ',', '.') }}</strong>
                </div>
            </div>

<form action="{{ route('pelunasan.upload-bukti') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kode_booking" value="{{ $kodeBooking }}">

                <div class="mb-5">
                    <label class="block text-[11px] font-extrabold text-slate-600 tracking-wider mb-2">UNGHAH FOTO BUKTI / SCREENSHOT (WAJIB)</label>
                    
                    <div id="dropzoneBox" class="relative group border-2 border-dashed border-slate-300 bg-slate-100/50 rounded-xl p-8 text-center cursor-pointer transition-all hover:border-blue-500 hover:bg-blue-50/30">
                        <span class="text-3xl block mb-2">📸</span>
                        <p id="dropzoneText" class="text-xs font-semibold text-slate-600">
                            Tarik screenshot ke sini atau <span class="text-blue-500 underline">pilih file dari galeri</span>
                        </p>
                        <p class="text-[10px] text-slate-400 mt-1">Mendukung format: JPG, JPEG, PNG (Maks. 2MB)</p>
                        
                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/*" required class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>

                    <div id="previewWrapper" class="mt-4 p-3 bg-slate-100 border border-slate-200 rounded-xl hidden text-center">
                        <span class="block text-[11px] font-bold text-slate-500 mb-2">Pratinjau Bukti Yang Dipilih:</span>
                        <div class="inline-block bg-white p-1 rounded-lg border border-slate-300 shadow-sm">
                            <img src="" alt="Pratinjau Struk" id="framePreview" class="max-h-60 rounded mx-auto object-contain">
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-[11px] font-extrabold text-slate-600 tracking-wider mb-2">CATATAN TAMBAHAN (OPSIONAL)</label>
                    <textarea name="catatan_pembayaran" rows="3" placeholder="Tuliskan catatan jika ada (Contoh: via Mandiri an. Ahmad)" class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none resize-none transition-all"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    <button type="submit" class="w-full inline-flex justify-center items-center py-3 bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 transition-all">
                        🚀 Kirim & Selesaikan Pembayaran
                    </button>
                    <a href="/customer/dashboard" class="w-full inline-flex justify-center items-center py-3 bg-slate-300 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-400/80 hover:text-slate-800 transition-all text-center">
                        ❌ Nanti Dulu
                    </a>
                </div>
            </form>

        </div>
    </div>

    <script>
        const fileInput = document.getElementById('bukti_pembayaran');
        const previewWrapper = document.getElementById('previewWrapper');
        const framePreview = document.getElementById('framePreview');
        const dropzoneText = document.getElementById('dropzoneText');
        const dropzoneBox = document.getElementById('dropzoneBox');

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                
                // Mengubah tampilan kotak dropzone menjadi aktif hijau
                dropzoneText.innerHTML = `File siap dikirim: <strong class="text-emerald-600">${file.name}</strong>`;
                dropzoneBox.classList.remove('border-slate-300');
                dropzoneBox.classList.add('border-emerald-500', 'bg-emerald-50/20');
                
                // Merender file gambar ke penampung img tag
                reader.addEventListener('load', function() {
                    framePreview.setAttribute('src', this.result);
                    previewWrapper.classList.remove('hidden');
                });
                
                reader.readAsDataURL(file);
            } else {
                previewWrapper.classList.add('hidden');
            }
        });
    </script>
</body>
</html>