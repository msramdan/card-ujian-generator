<?php

namespace App\ExcelBinders;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class StringValueBinder extends DefaultValueBinder
{
    public function bindValue(Cell $cell, $value)
    {
        // Jika kolomnya 'B' (asumsi NIS di kolom B, sesuaikan jika berbeda)
        // atau jika Anda ingin semua nilai dibaca sebagai string (lebih aman untuk NIS, NIK, dll.)
        // Untuk lebih spesifik, Anda bisa memeriksa $cell->getColumn()
        if (is_numeric($value) && !is_float($value)) { // Hanya untuk integer
            // Cek apakah header kolom adalah 'nis' (membutuhkan akses ke heading row)
            // Ini lebih kompleks jika tidak menggunakan WithMappedCells
            // Sebagai alternatif mudah, kita paksakan semua numerik non-float jadi string
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
