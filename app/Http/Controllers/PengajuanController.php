<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PengajuanController extends Controller
{
    /**
     * Tampilkan form pengajuan untuk satu beasiswa.
     */
    public function create(int $id): View
    {
        $beasiswa = DB::table('beasiswa')->where('id', $id)->first();
        abort_if(! $beasiswa, 404);

        return view('apply', compact('beasiswa'));
    }

    /**
     * Simpan pengajuan beasiswa milik user yang sedang login.
     */
    public function store(Request $request, int $id): RedirectResponse
    {
        $beasiswa = DB::table('beasiswa')->where('id', $id)->first();
        abort_if(! $beasiswa, 404);

        $data = $request->validate([
            'NamaUniversitas' => ['required', 'string', 'max:255'],
            'Semester'        => ['required', 'integer', 'between:1,14'],
            'IPK'             => ['required', 'numeric', 'between:0,4'],
            'AlamatUniv'      => ['required', 'string'],
            'document'        => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        // Validasi aturan bisnis
        if ($beasiswa->status !== 'dibuka') {
            return $this->tolak('Pendaftaran beasiswa ini sudah ditutup.');
        }

        if (Carbon::parse($beasiswa->Dibuka)->isFuture()) {
            return $this->tolak('Pendaftaran beasiswa ini belum dibuka.');
        }

        if (Carbon::parse($beasiswa->Ditutup)->isPast()) {
            return $this->tolak('Batas waktu pendaftaran sudah lewat.');
        }

        if ($beasiswa->SudahMasuk >= $beasiswa->JumlahKursi) {
            return $this->tolak('Kuota beasiswa ini sudah penuh.');
        }

        $sudahMengajukan = DB::table('memiliki')
            ->where('id_user', auth()->id())
            ->where('id_beasiswa', $beasiswa->id)
            ->exists();

        if ($sudahMengajukan) {
            return $this->tolak('Kamu sudah mengajukan beasiswa ini.');
        }

        // Simpan file ke storage/app/public/berkas-pengajuan
        $file = $request->file('document');
        $path = $file->store('berkas-pengajuan', 'public');

        try {
            // TanggalDaftar dan StatusDiterima ('menunggu') diisi otomatis oleh default kolom
            DB::table('memiliki')->insert([
                'id_user'         => auth()->id(),
                'id_beasiswa'     => $beasiswa->id,
                'NamaUniversitas' => $data['NamaUniversitas'],
                'Semester'        => $data['Semester'],
                'ipk'             => $data['IPK'],
                'AlamatUniv'      => $data['AlamatUniv'],
                'UploadFilePath'  => $path,
                'NamaFile'        => $file->getClientOriginalName(),
            ]);
        } catch (\Throwable $e) {
            // Insert gagal: hapus file yang sudah terlanjur diunggah
            Storage::disk('public')->delete($path);
            throw $e;
        }

        return redirect()
            ->route('dashboard')
            ->with('status', 'Pengajuan beasiswa berhasil dikirim.');
    }

    private function tolak(string $pesan): RedirectResponse
    {
        return back()->withErrors(['beasiswa' => $pesan])->withInput();
    }
}