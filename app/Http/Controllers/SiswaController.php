<?php

namespace App\Http\Controllers;

use App\Models\{Siswa, Jurusan, Kelas};
use App\Http\Requests\Siswas\{StoreSiswaRequest, UpdateSiswaRequest, ImportSiswaRequest};
use Illuminate\Contracts\View\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Abort;
use Maatwebsite\Excel\Facades\Excel;
use App\FormatImport\FormatImportSiswa;
use App\Imports\ImportSiswa;
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth', // Umumnya sudah dihandle di group route
            new Middleware('permission:siswa view', only: ['index', 'show']),
            new Middleware('permission:siswa create', only: ['create', 'store']),
            new Middleware('permission:siswa edit', only: ['edit', 'update']),
            new Middleware('permission:siswa delete', only: ['destroy']),
            // Tambahkan permission untuk import dan export jika belum ada
            new Middleware('permission:siswa export', only: ['exportSiswa']),
            new Middleware('permission:siswa import', only: ['importSiswa', 'formatImportSiswa']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View | JsonResponse // Menambahkan Request $request
    {
        if ($request->ajax()) { // Menggunakan $request->ajax() bukan request()->ajax() untuk konsistensi
            $siswas = DB::table('siswa')
                ->leftJoin('jurusan', 'siswa.jurusan_id', '=', 'jurusan.id')
                ->leftJoin('kelas', 'siswa.kelas_id', '=', 'kelas.id')
                ->select(
                    'siswa.id',
                    'siswa.nama_siswa',
                    'siswa.nis',
                    'jurusan.nama_jurusan',
                    'kelas.nama_kelas',
                    'siswa.password'
                );

            return DataTables::of($siswas)
                ->addColumn('nama_jurusan', function ($row) {
                    return $row->nama_jurusan ?? '-'; // Memberi nilai default jika null
                })
                ->addColumn('nama_kelas', function ($row) {
                    return $row->nama_kelas ?? '-'; // Memberi nilai default jika null
                })
                ->addColumn('action', 'siswa.include.action') // Pastikan view ini ada
                ->rawColumns(['action'])
                ->make(true); // Menggunakan make(true) untuk otomatisasi
        }

        return view('siswa.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Mengambil data jurusan dan kelas untuk dropdown
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('siswa.create', compact('jurusans', 'kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSiswaRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        // Password disimpan sebagai plain text sesuai permintaan
        // $validated['password'] = $validated['password']; // Tidak perlu di-assign ulang jika sudah tervalidasi
        $validated['token'] = Str::random(20); // Token acak 20 karakter (dari kode asli)

        Siswa::create($validated);

        return to_route('siswa.index')->with('success', __('Siswa berhasil dibuat.'));
    }

    /**
     * Display the specified resource.
     * Menggunakan Route Model Binding lebih disarankan, tetapi mengikuti pola kode asli
     */
    public function show($idSiswa): View // Mengubah nama parameter agar tidak bentrok dengan $id model
    {
        $siswa = Siswa::with(['jurusan', 'kelas'])->findOrFail($idSiswa); // Menggunakan Eloquent untuk kemudahan

        // URL untuk QR Code (menggunakan token dari database)
        $url = route('kartu-peserta.kartu', [
            'id' => $siswa->id,
            'token' => $siswa->token, // Mengambil token dari model Siswa
        ]);

        $renderer = new ImageRenderer(
            new RendererStyle(200, 1), // margin: 1
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrSvg = $writer->writeString($url);

        return view('siswa.show', compact('siswa', 'qrSvg'));
    }


    public function kartu(Request $request, $idSiswa): View // Mengubah nama parameter
    {
        $token = $request->query('token');
        $siswa = Siswa::with(['jurusan', 'kelas'])->find($idSiswa); // Menggunakan Eloquent

        if (!$siswa) {
            abort(404, 'Siswa tidak ditemukan');
        }

        if ($siswa->token !== $token) {
            abort(403, 'Token tidak valid. Pastikan token pada QR Code sesuai.');
        }

        $url = route('kartu-peserta.kartu', [
            'id' => $siswa->id,
            'token' => $siswa->token,
        ]);

        $renderer = new ImageRenderer(
            new RendererStyle(110, 1), // margin: 1
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrSvg = $writer->writeString($url);

        return view('siswa.kartu', compact('siswa', 'qrSvg'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa): View // Menggunakan Route Model Binding
    {
        // $siswa->load(['jurusan:id,nama_jurusan', 'kelas:id,nama_kelas']); // 'kela' sepertinya typo, diganti 'kelas'
        // Jika relasi di model Siswa adalah 'jurusan' dan 'kelas'
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('siswa.edit', compact('siswa', 'jurusans', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiswaRequest $request, Siswa $siswa): RedirectResponse
    {
        $validated = $request->validated();
        // Password tidak di-hash jika diubah, disimpan sebagai plain text
        if (empty($validated['password'])) {
            unset($validated['password']); // Jangan update password jika kosong
        }
        // Token tidak diubah saat update, kecuali ada logika khusus
        $siswa->update($validated);
        return to_route('siswa.index')->with('success', __('Siswa berhasil diperbarui.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa): RedirectResponse
    {
        try {
            $siswa->delete();
            return to_route('siswa.index')->with('success', __('Siswa berhasil dihapus.'));
        } catch (\Exception $e) {
            Log::error("Error deleting siswa: " . $e->getMessage()); // Logging error
            return to_route('siswa.index')->with('error', __("Siswa tidak dapat dihapus karena mungkin terkait dengan data lain."));
        }
    }

    // --- METODE UNTUK IMPORT DAN EKSPOR ---

    /**
     * Export data siswa to Excel.
     */
    public function exportSiswa()
    {
        $siswas = Siswa::with(['jurusan', 'kelas'])->get();
        if ($siswas->isEmpty()) {
            return redirect()->route('siswa.index')->with('error', 'Tidak ada data siswa untuk diekspor.');
        }

        $dataToExport = [];
        // Header
        $dataToExport[] = ['Nama Siswa', 'NIS', 'Jurusan', 'Kelas', 'Password/Hak Akses', 'Token'];

        foreach ($siswas as $siswa) {
            $dataToExport[] = [
                $siswa->nama_siswa,
                $siswa->nis,
                $siswa->jurusan ? $siswa->jurusan->nama_jurusan : '',
                $siswa->kelas ? $siswa->kelas->nama_kelas : '',
                $siswa->password, // Menampilkan password/hak akses
                $siswa->token,
            ];
        }

        $fileName = 'data_siswa_' . date('YmdHis') . '.xlsx';

        return Excel::download(new class($dataToExport) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\ShouldAutoSize, \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $data;
            public function __construct(array $data)
            {
                $this->data = $data;
            }
            public function headings(): array
            {
                return $this->data[0];
            } // Ambil header dari baris pertama data
            public function array(): array
            {
                return array_slice($this->data, 1);
            } // Ambil data setelah header
        }, $fileName);
    }

    /**
     * Handle import of siswa from Excel.
     */
    public function importSiswa(ImportSiswaRequest $request): RedirectResponse // Menggunakan ImportSiswaRequest yang sudah dibuat
    {
        try {
            $file = $request->file('import_file_siswa'); // Sesuai dengan nama input di view
            $import = new ImportSiswa(); // Menggunakan class ImportSiswa yang akan direvisi
            Excel::import($import, $file);

            $importedCount = $import->getImportedCount();
            $skippedCount = $import->getSkippedCount();
            $errors = $import->getErrors();

            $message = "Berhasil mengimpor {$importedCount} data siswa.";
            if ($skippedCount > 0) {
                $message .= " {$skippedCount} data dilewati.";
            }

            if (!empty($errors)) {
                $errorMessages = implode("<br>", array_map(fn($err) => htmlspecialchars($err), $errors));
                return redirect()->route('siswa.index')
                    ->with('success', $message)
                    ->with('error', "Terjadi beberapa kesalahan saat impor:<br>{$errorMessages}");
            }

            return redirect()->route('siswa.index')->with('success', $message);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $attribute = $failure->attribute() ?? 'Tidak diketahui';
                $value = isset($failure->values()[$attribute]) ? htmlspecialchars($failure->values()[$attribute]) : 'N/A';
                $errorMessages[] = "Baris " . $failure->row() . " (Kolom: " . htmlspecialchars($attribute) . "): " . implode(", ", $failure->errors()) . " (Nilai: " . $value . ")";
            }
            return redirect()->route('siswa.index')->with('error', "Error validasi data Excel:<br>" . implode("<br>", $errorMessages));
        } catch (\Exception $e) {
            Log::error('Error importing siswa: ' . $e->getMessage() . ' Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('siswa.index')->with('error', 'Terjadi kesalahan umum saat mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel format for importing siswa.
     */
    public function formatImportSiswa()
    {
        try {
            $fileName = 'format_import_siswa_' . date('YmdHis') . '.xlsx';
            return Excel::download(new FormatImportSiswa(), $fileName); // Menggunakan FormatImportSiswa dari respons sebelumnya
        } catch (\Exception $e) {
            Log::error('Error downloading import format siswa: ' . $e->getMessage());
            return redirect()->route('siswa.index')->with('error', 'Gagal mengunduh format impor siswa: ' . $e->getMessage());
        }
    }
}
