<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\RuangUjian;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Str;

use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use App\ExcelBinders\StringValueBinder;

class ImportSiswa implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows, WithCustomValueBinder
{
    private $errors = [];
    private $importedCount = 0;
    private $skippedCount = 0;
    private $jurusanMap = [];
    private $kelasMap = [];
    private $ruangUjianMap = [];

    public function __construct()
    {
        $this->jurusanMap = Jurusan::pluck('id', 'nama_jurusan')->toArray();
        $this->kelasMap = Kelas::pluck('id', 'nama_kelas')->toArray();
        $this->ruangUjianMap = RuangUjian::pluck('id', 'nama_ruang_ujian')->toArray();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $rowIndex => $row) {
            // Membersihkan spasi ekstra dari input
            $rowData = $row->map(function ($item) {
                return is_string($item) ? trim($item) : $item;
            })->all();

            $validator = Validator::make($rowData, $this->rules($rowIndex), $this->customValidationMessages());

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $this->errors[] = "Baris " . ($rowIndex + 2) . ": " . $error;
                }
                $this->skippedCount++;
                continue;
            }

            $namaJurusan = $rowData['jurusan'];
            $namaKelas = $rowData['kelas'];
            $namaRuangUjian = $rowData['ruang_ujian'];

            if (!isset($this->jurusanMap[$namaJurusan])) {
                $this->errors[] = "Baris " . ($rowIndex + 2) . ": Jurusan '" . htmlspecialchars($namaJurusan) . "' tidak ditemukan di sistem.";
                $this->skippedCount++;
                continue;
            }

            if (!isset($this->kelasMap[$namaKelas])) {
                $this->errors[] = "Baris " . ($rowIndex + 2) . ": Kelas '" . htmlspecialchars($namaKelas) . "' tidak ditemukan di sistem.";
                $this->skippedCount++;
                continue;
            }

            if (!isset($this->ruangUjianMap[$namaRuangUjian])) {
                $this->errors[] = "Baris " . ($rowIndex + 2) . ": Ruang Ujian '" . htmlspecialchars($namaRuangUjian) . "' tidak ditemukan di sistem.";
                $this->skippedCount++;
                continue;
            }

            if (Siswa::where('nis', $rowData['nis'])->exists()) {
                $this->errors[] = "Baris " . ($rowIndex + 2) . ": NIS '" . htmlspecialchars($rowData['nis']) . "' sudah terdaftar.";
                $this->skippedCount++;
                continue;
            }

            try {
                Siswa::create([
                    'nama_siswa' => $rowData['nama_siswa'],
                    'nis'        => $rowData['nis'],
                    'jurusan_id' => $this->jurusanMap[$namaJurusan],
                    'kelas_id'   => $this->kelasMap[$namaKelas],
                    'ruang_ujian_id' => $this->ruangUjianMap[$namaRuangUjian],
                    'password'   => $rowData['password'],
                    'token'      => Str::random(20),
                ]);
                $this->importedCount++;
            } catch (\Exception $e) {
                $this->errors[] = "Baris " . ($rowIndex + 2) . ": Gagal menyimpan data siswa '" . htmlspecialchars($rowData['nama_siswa']) . "'. Error: " . $e->getMessage();
                $this->skippedCount++;
            }
        }
    }

    public function rules($rowIndex = null): array
    {
        return [
            'nama_siswa' => 'required|string|max:255',
            'nis'        => 'required|string|max:100',
            'jurusan'    => 'required|string',
            'kelas'      => 'required|string',
            'ruang_ujian' => 'required|string', // Diubah dari nullable menjadi required
            'password'   => 'required|string|max:50',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_siswa.required' => 'Kolom nama_siswa wajib diisi.',
            'nis.required'        => 'Kolom nis wajib diisi.',
            'jurusan.required'    => 'Kolom jurusan wajib diisi.',
            'kelas.required'      => 'Kolom kelas wajib diisi.',
            'ruang_ujian.required' => 'Kolom ruang_ujian wajib diisi.', // Pesan validasi baru
            'password.required'   => 'Kolom password wajib diisi.',
            'password.max'        => 'Kolom password maksimal 50 karakter.',
        ];
    }

    public function bindValue(\PhpOffice\PhpSpreadsheet\Cell\Cell $cell, $value)
    {
        return (new StringValueBinder)->bindValue($cell, $value);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }
}
