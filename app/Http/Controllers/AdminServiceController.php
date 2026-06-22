<?php

namespace App\Http\Controllers;

use App\Models\JasaLayanan;
use Illuminate\Http\Request;

class AdminServiceController extends Controller
{
    private function guardAdmin(): void
    {
        if (!session('role') || session('role') !== 'admin') {
            abort(403);
        }
    }

    public function index()
    {
        $this->guardAdmin();
        // Jika tabel belum ada, tampilkan halaman kosong (hindari 404 yang bikin menu terasa hilang)
        if (!\Illuminate\Support\Facades\Schema::hasTable('jasa_layanans')) {
            return view('admin.data-layanan', ['services' => collect()]);
        }

        $services = JasaLayanan::orderBy('id_jasa')->get();
        return view('admin.data-layanan', compact('services'));
    }

    public function store(Request $request)
    {
        $this->guardAdmin();

        $data = $request->validate([
            'id_jasa' => 'required|string|max:10|unique:jasa_layanans,id_jasa',
            'nama_jasa' => 'required|string|max:255',
            'estimasi_harga' => 'required|integer|min:0',
        ]);

        if (($data['id_jasa'] ?? null) === 'L00') {
            return redirect('/admin/data-layanan')->with('error', 'ID L00 tidak boleh dibuat ulang.');
        }

        JasaLayanan::create([
            'id_jasa' => $data['id_jasa'],
            'nama_jasa' => $data['nama_jasa'],
            'estimasi_harga' => (int) $data['estimasi_harga'],
            'is_locked' => false,
        ]);

        return redirect('/admin/data-layanan')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit($id_jasa)
    {
        $this->guardAdmin();

        $service = JasaLayanan::where('id_jasa', $id_jasa)->firstOrFail();
        return view('admin.data-layanan-edit', compact('service'));
    }

    public function update(Request $request, $id_jasa)
    {
        $this->guardAdmin();

        $service = JasaLayanan::where('id_jasa', $id_jasa)->firstOrFail();
        if ($service->is_locked) {
            return redirect('/admin/data-layanan')->with('error', 'ID L00 tidak boleh diubah atau dihapus.');
        }

        $data = $request->validate([
            'nama_jasa' => 'required|string|max:255',
            'estimasi_harga' => 'required|integer|min:0',
        ]);

        $service->nama_jasa = $data['nama_jasa'];
        $service->estimasi_harga = (int) $data['estimasi_harga'];
        $service->save();

        return redirect('/admin/data-layanan')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy($id_jasa)
    {
        $this->guardAdmin();

        $service = JasaLayanan::where('id_jasa', $id_jasa)->firstOrFail();
        if ($service->is_locked) {
            return redirect('/admin/data-layanan')->with('error', 'ID L00 tidak boleh dihapus.');
        }

        $service->delete();
        return redirect('/admin/data-layanan')->with('success', 'Layanan berhasil dihapus.');
    }
}

