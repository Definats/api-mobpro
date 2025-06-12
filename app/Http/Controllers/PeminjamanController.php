<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Storage;

class PeminjamanController extends Controller
{
    public function index()
    {
        $data = Peminjaman::all();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $email = $request->header('Authorization'); // <- ambil dari header
        // if($email){
            $request->validate([
                'nama' => 'required|string|max:255',
                'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $path = $request->file('gambar')->store('gambar-peminjaman', 'public');

            Peminjaman::create([
                'nama' => $request->nama,
                'gambar' => $path,
                'email' => $email,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil ditambahkan.'
            ]);
        // }
    }

    public function update(Request $request, $id)
    {
        $email = $request->header('Authorization'); // Ambil email dari header

        // Validasi data input
        $request->validate([
            'nama' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Cari data berdasarkan id dan email
        $peminjaman = Peminjaman::where('id', $id)
            ->where('email', $email)
            ->first();

        if (!$peminjaman) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan atau Anda tidak memiliki akses.'
            ], 404);
        }

        // Update nama
        $peminjaman->nama = $request->nama;

        // Jika ada gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($peminjaman->gambar && Storage::disk('public')->exists($peminjaman->gambar)) {
                Storage::disk('public')->delete($peminjaman->gambar);
            }

            // Simpan gambar baru
            $path = $request->file('gambar')->store('gambar-peminjaman', 'public');
            $peminjaman->gambar = $path;
        }

        // Simpan perubahan
        $peminjaman->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $email = $request->header('Authorization'); // Ambil email dari header

        // Cari data berdasarkan id dan email
        $peminjaman = Peminjaman::where('id', $id)
            ->where('email', $email)
            ->first();

        if (!$peminjaman) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan atau Anda tidak memiliki akses.'
            ], 404);
        }

        // Hapus file gambar jika ada
        if ($peminjaman->gambar && Storage::disk('public')->exists($peminjaman->gambar)) {
            Storage::disk('public')->delete($peminjaman->gambar);
        }

        // Hapus data dari database
        $peminjaman->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus.'
        ]);
    }
}
