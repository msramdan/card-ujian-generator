<?php

namespace App\Http\Requests\Siswas;

use Illuminate\Foundation\Http\FormRequest;

class ImportSiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Atur ke true jika semua pengguna yang terautentikasi bisa impor,
        // atau tambahkan logika permission di sini (misal: $this->user()->can('siswa import'))
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Nama input file disesuaikan dengan yang ada di view
            'import_file_siswa' => 'required|file|mimes:xlsx|max:2048', // Max 2MB
        ];
    }

    public function messages(): array
    {
        return [
            'import_file_siswa.required' => 'File impor wajib diunggah.',
            'import_file_siswa.file'     => 'File impor harus berupa berkas.',
            'import_file_siswa.mimes'    => 'File impor harus berformat .xlsx.',
            'import_file_siswa.max'      => 'Ukuran file impor maksimal 2MB.',
        ];
    }
}
