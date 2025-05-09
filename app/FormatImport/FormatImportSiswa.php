<?php

namespace App\FormatImport;

use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class FormatImportSiswa implements FromView, ShouldAutoSize, WithEvents, WithStrictNullComparison, WithTitle
{
    public function title(): string
    {
        return 'Format Impor Siswa';
    }

    public function view(): View
    {
        // Data ini akan digunakan untuk contoh di file Excel dan dropdown
        $contohJurusan = Jurusan::take(5)->pluck('nama_jurusan')->toArray();
        $contohKelas = Kelas::take(5)->pluck('nama_kelas')->toArray();

        return view('siswa.include.format_import_view', [
            'contohJurusan' => $contohJurusan,
            'contohKelas' => $contohKelas,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Header
                $headerRange = 'A1:E1';
                $sheet->getStyle($headerRange)->getFont()->setBold(true);
                $sheet->getStyle($headerRange)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFFE0'); // Kuning muda

                // Kolom yang dibutuhkan: nama_siswa, nis, jurusan, kelas, password
                $columns = ['nama_siswa', 'nis', 'jurusan', 'kelas', 'password'];
                foreach ($columns as $index => $columnName) {
                    $cellCoordinate = chr(65 + $index) . '1'; // A1, B1, C1, dst.
                    $sheet->setCellValue($cellCoordinate, $columnName);
                }

                // Data Validation untuk Jurusan (Kolom C)
                $jurusanList = Jurusan::pluck('nama_jurusan')->toArray();
                if (!empty($jurusanList)) {
                    for ($i = 2; $i <= 100; $i++) { // Terapkan ke 99 baris data
                        $validationJurusan = $sheet->getCell("C{$i}")->getDataValidation();
                        $validationJurusan->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_WARNING)
                            ->setAllowBlank(false)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle('Input salah')
                            ->setError('Nilai tidak ada dalam daftar.')
                            ->setPromptTitle('Pilih dari daftar')
                            ->setPrompt('Pilih jurusan dari daftar yang tersedia.')
                            ->setFormula1('"' . implode(',', $jurusanList) . '"');
                    }
                }

                // Data Validation untuk Kelas (Kolom D)
                $kelasList = Kelas::pluck('nama_kelas')->toArray();
                if (!empty($kelasList)) {
                    for ($i = 2; $i <= 100; $i++) { // Terapkan ke 99 baris data
                        $validationKelas = $sheet->getCell("D{$i}")->getDataValidation();
                        $validationKelas->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_WARNING)
                            ->setAllowBlank(false)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle('Input salah')
                            ->setError('Nilai tidak ada dalam daftar.')
                            ->setPromptTitle('Pilih dari daftar')
                            ->setPrompt('Pilih kelas dari daftar yang tersedia.')
                            ->setFormula1('"' . implode(',', $kelasList) . '"');
                    }
                }
            },
        ];
    }
}
