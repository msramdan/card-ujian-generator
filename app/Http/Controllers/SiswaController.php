<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Http\Requests\Siswas\{StoreSiswaRequest, UpdateSiswaRequest};
use Illuminate\Contracts\View\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class SiswaController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',

            // TODO: uncomment this code if you are using spatie permission
            // new Middleware('permission:siswa view', only: ['index', 'show']),
            // new Middleware('permission:siswa create', only: ['create', 'store']),
            // new Middleware('permission:siswa edit', only: ['edit', 'update']),
            // new Middleware('permission:siswa delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            $siswas = DB::table('siswa')
                ->leftJoin('jurusan', 'siswa.jurusan_id', '=', 'jurusan.id')
                ->leftJoin('kelas', 'siswa.kelas_id', '=', 'kelas.id')
                ->select(
                    'siswa.*',
                    'jurusan.nama_jurusan',
                    'kelas.nama_kelas'
                );

            return DataTables::of($siswas)
                ->addColumn('nama_jurusan', function ($row) {
                    return $row->nama_jurusan ?? '';
                })
                ->addColumn('nama_kelas', function ($row) {
                    return $row->nama_kelas ?? '';
                })
                ->addColumn('action', 'siswa.include.action')
                ->rawColumns(['action']) // jika ada HTML pada kolom action
                ->toJson();
        }

        return view('siswa.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('siswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSiswaRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['token'] = Str::random(20); // Token acak 20 karakter

        Siswa::create($validated);

        return to_route('siswa.index')->with('success', __('The siswa was created successfully.'));
    }

    public function show($id): View
    {
        // Mengambil data siswa dengan join ke jurusan dan kelas
        $siswa = DB::table('siswa')
            ->select('siswa.*', 'jurusan.nama_jurusan', 'kelas.nama_kelas')
            ->leftJoin('jurusan', 'siswa.jurusan_id', '=', 'jurusan.id')
            ->leftJoin('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->where('siswa.id', $id)
            ->first();

        // Generate URL untuk download kartu peserta
        $url = route('kartu-peserta.download', [
            'id' => $siswa->id,
            'token' => $siswa->token,
        ]);

        // Generate QR Code dalam format SVG
        $renderer = new ImageRenderer(
            new RendererStyle(200, margin: 1),  // Menentukan ukuran QR code
            new SvgImageBackEnd()    // Menggunakan format SVG
        );

        $writer = new Writer($renderer);
        $qrSvg = $writer->writeString($url);  // Menghasilkan QR code dalam bentuk SVG

        // Return view dengan data siswa dan QR code
        return view('siswa.show', compact('siswa', 'qrSvg'));
    }

    public function download($id): View
    {
        // Mengambil data siswa dengan join ke jurusan dan kelas
        $siswa = DB::table('siswa')
            ->select('siswa.*', 'jurusan.nama_jurusan', 'kelas.nama_kelas')
            ->leftJoin('jurusan', 'siswa.jurusan_id', '=', 'jurusan.id')
            ->leftJoin('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->where('siswa.id', $id)
            ->first();

        // Generate URL untuk download kartu peserta
        $url = route('kartu-peserta.download', [
            'id' => $siswa->id,
            'token' => $siswa->token,
        ]);

        // Generate QR Code dalam format SVG
        $renderer = new ImageRenderer(
            new RendererStyle(110, margin: 1),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        $qrSvg = $writer->writeString($url);  // Menghasilkan QR code dalam bentuk SVG

        // Return view dengan data siswa dan QR code
        return view('siswa.kartu', compact('siswa', 'qrSvg'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa): View
    {
        $siswa->load(['jurusan:id,nama_jurusan', 'kela:id,nama_kelas']);

        return view('siswa.edit', compact('siswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiswaRequest $request, Siswa $siswa): RedirectResponse
    {

        $siswa->update($request->validated());

        return to_route('siswa.index')->with('success', __('The siswa was updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa): RedirectResponse
    {
        try {
            $siswa->delete();

            return to_route('siswa.index')->with('success', __('The siswa was deleted successfully.'));
        } catch (\Exception $e) {
            return to_route('siswa.index')->with('error', __("The siswa can't be deleted because it's related to another table."));
        }
    }
}
