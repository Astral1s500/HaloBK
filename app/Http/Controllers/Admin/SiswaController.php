<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    /**
     * Menampilkan semua siswa (aktif atau arsip)
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'aktif');
        $totalAktif = Siswa::count();
        $totalArsip = Siswa::onlyTrashed()->count();

        if ($status === 'arsip') {
            $siswas = Siswa::onlyTrashed()->with('kelas', 'admin')->latest('deleted_at')->get();
        } else {
            $status = 'aktif';
            $siswas = Siswa::with('kelas', 'admin')->latest()->get();
        }

        return view('admin.siswa.index', compact('siswas', 'status', 'totalAktif', 'totalArsip'));
    }

    /**
     * Menampillkan form tambah siswa baru
     */
    public function create()
    {
        // Kelas awal tidak dipilih saat pembuatan — cukup tampilkan form kosong
        return view('admin.siswa.create');
    }

    /**
     * Menyimpan data siswa baru ke database
     */
    public function store(Request $request)
    {
        // Validasi tanpa id_kelas — kelas akan ditetapkan nanti oleh Guru BK
        $request->validate([
            'nama_siswa' => ['required', 'string', 'max:255'],
            'nisn'       => ['required', 'string', 'unique:siswas,nisn', 'max:50'],
            'password'   => ['required', 'string', 'min:6'],
        ], [
            'nama_siswa.required' => 'Nama Siswa wajib diisi.',
            'nisn.required'       => 'NISN wajib diisi.',
            'nisn.unique'         => 'NISN sudah terdaftar.',
            'password.required'   => 'Password wajib diisi.',
            'password.min'        => 'Password minimal 6 karakter.',
        ]);

        Siswa::create([
            'nama_siswa' => $request->nama_siswa,
            'nisn'       => $request->nisn,
            'id_kelas'   => null, // Belum punya kelas, akan diisi nanti
            'id_admin'   => Auth::guard('admin')->id(),
            'password'   => Hash::make($request->password),
        ]);

        return redirect()->route('admin.siswa.index')->with('success', 'Data Siswa berhasil ditambahkan.');
    }

    /**
     * Menampilkan edit data siswa
     */
    public function edit(Siswa $siswa)
    {
        $kelasList = Kelas::with('guruBk')->orderBy('nama_kelas')->get();
        return view('admin.siswa.edit', compact('siswa', 'kelasList'));
    }

    /**
     * Memperbarui data siswa ke database
     */
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama_siswa' => ['required', 'string', 'max:255'],
            'nisn'       => ['required', 'string', 'max:50', 'unique:siswas,nisn,' . $siswa->id_siswa . ',id_siswa'],
            'id_kelas'   => ['nullable', 'exists:kelas,id_kelas'],
            'password'   => ['nullable', 'string', 'min:6'],
        ], [
            'nama_siswa.required' => 'Nama Siswa wajib diisi.',
            'nisn.required'       => 'NISN wajib diisi.',
            'nisn.unique'         => 'NISN sudah digunakan oleh siswa lain.',
            'id_kelas.exists'     => 'Kelas yang dipilih tidak valid.',
            'password.min'        => 'Password minimal 6 karakter jika ingin diganti.',
        ]);

        $data = [
            'nama_siswa' => $request->nama_siswa,
            'nisn'       => $request->nisn,
            'id_kelas'   => $request->filled('id_kelas') ? $request->id_kelas : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $siswa->update($data);

        return redirect()->route('admin.siswa.index')->with('success', 'Data Siswa berhasil diperbarui.');
    }

    /**
     * Menonaktifkan / mengarsipkan data siswa (Soft Delete)
     */
    public function destroy(Siswa $siswa)
    {
        $nama = $siswa->nama_siswa;
        $siswa->delete();
        return redirect()->route('admin.siswa.index')->with('success', "Akun siswa {$nama} berhasil dinonaktifkan dan dipindahkan ke Arsip Siswa. Riwayat ujian dan nilainya tetap aman.");
    }

    /**
     * Memulihkan akun siswa dari arsip kembali ke status aktif
     */
    public function restore($id)
    {
        $siswa = Siswa::onlyTrashed()->findOrFail($id);
        $siswa->restore();

        return redirect()->route('admin.siswa.index', ['status' => 'arsip'])->with('success', "Akun siswa {$siswa->nama_siswa} berhasil dipulihkan kembali ke status aktif.");
    }

    /**
     * Menghapus permanen data siswa beserta seluruh riwayatnya (Force Delete)
     */
    public function forceDelete($id)
    {
        $siswa = Siswa::onlyTrashed()->findOrFail($id);
        $nama = $siswa->nama_siswa;
        $siswa->forceDelete();

        return redirect()->route('admin.siswa.index', ['status' => 'arsip'])->with('success', "Data siswa {$nama} telah dihapus permanen dari basis data.");
    }

    /**
     * Ekspor seluruh data siswa ke format Excel / CSV
     */
    public function exportExcel()
    {
        $siswas = Siswa::with('kelas')->orderBy('nama_siswa', 'asc')->get();
        $filename = 'Data_Siswa_HaloBK_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($siswas) {
            $handle = fopen('php://output', 'w');
            
            // Masukkan UTF-8 BOM agar Microsoft Excel membaca format teks dengan benar tanpa encoding error
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Baris Judul Kolom
            fputcsv($handle, ['No', 'NISN', 'Nama Siswa', 'Kelas', 'Status Akun', 'Tanggal Terdaftar']);

            $no = 1;
            foreach ($siswas as $siswa) {
                fputcsv($handle, [
                    $no++,
                    (string) $siswa->nisn,
                    $siswa->nama_siswa,
                    $siswa->kelas ? $siswa->kelas->nama_kelas : 'Belum Ada Kelas',
                    $siswa->deleted_at ? 'Arsip / Nonaktif' : 'Aktif',
                    $siswa->created_at ? $siswa->created_at->format('d/m/Y H:i') : '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Download Template Excel / CSV untuk Import Siswa Massal
     */
    public function downloadTemplate()
    {
        $filename = 'Template_Import_Siswa_HaloBK.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom template
            fputcsv($handle, ['NISN', 'Nama Siswa', 'Password']);

            // Contoh data pengisian
            fputcsv($handle, ['0081234567', 'Ahmad Fauzi', 'password123']);
            fputcsv($handle, ['0087654321', 'Siti Nurhaliza', 'siswa2026']);
            fputcsv($handle, ['0089998888', 'Budi Santoso', 'siswa123']);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import banyak siswa sekaligus dari file Excel / CSV
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:csv,txt', 'max:5120'], // Max 5MB
        ], [
            'file_excel.required' => 'Silakan pilih file Excel / CSV yang akan diunggah.',
            'file_excel.mimes'    => 'Format file harus berupa CSV (.csv). Gunakan template yang disediakan.',
            'file_excel.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $file = $request->file('file_excel');
        $handle = fopen($file->getRealPath(), 'r');

        if (!$handle) {
            return redirect()->back()->with('error', 'Gagal membuka file yang diunggah.');
        }

        $importedCount = 0;
        $skippedCount = 0;
        $rowNumber = 0;
        $errorsList = [];
        $adminId = Auth::guard('admin')->id();

        // Deteksi delimiter (koma atau titik koma)
        $firstLine = fgets($handle);
        $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
        rewind($handle);

        while (($row = fgetcsv($handle, 2000, $delimiter)) !== false) {
            $rowNumber++;

            // Abaikan header (baris 1) jika berisi kata NISN / Nama
            if ($rowNumber === 1) {
                $firstCol = trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[0] ?? ''));
                if (stripos($firstCol, 'nisn') !== false || stripos($row[1] ?? '', 'nama') !== false) {
                    continue;
                }
            }

            // Validasi kelengkapan kolom
            $nisn = isset($row[0]) ? trim($row[0]) : '';
            $nama = isset($row[1]) ? trim($row[1]) : '';
            $password = isset($row[2]) && !empty(trim($row[2])) ? trim($row[2]) : 'password123'; // Default password jika kosong

            // Bersihkan format tanda petik atau karakter aneh
            $nisn = preg_replace('/[^0-9A-Za-z_-]/', '', $nisn);

            if (empty($nisn) || empty($nama)) {
                $skippedCount++;
                continue;
            }

            // Periksa apakah NISN sudah terdaftar
            $exists = Siswa::withTrashed()->where('nisn', $nisn)->exists();
            if ($exists) {
                $skippedCount++;
                $errorsList[] = "Baris {$rowNumber}: NISN {$nisn} sudah terdaftar, dilewati.";
                continue;
            }

            try {
                Siswa::create([
                    'nama_siswa' => $nama,
                    'nisn'       => $nisn,
                    'id_kelas'   => null,
                    'id_admin'   => $adminId,
                    'password'   => Hash::make($password),
                ]);
                $importedCount++;
            } catch (\Exception $e) {
                $skippedCount++;
                $errorsList[] = "Baris {$rowNumber}: Gagal menyimpan siswa {$nama}.";
            }
        }

        fclose($handle);

        $message = "Proses import selesai. Berhasil menambahkan {$importedCount} siswa baru.";
        if ($skippedCount > 0) {
            $message .= " Dilewati {$skippedCount} baris (karena NISN ganda atau data kosong).";
        }

        return redirect()->route('admin.siswa.index')->with('success', $message);
    }
}
