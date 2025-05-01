<?php

namespace App\Http\Requests\Siswas;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nama_siswa' => 'required|string|max:255',
			'nis' => 'required|string|max:15',
			'jurusan_id' => 'required|exists:App\Models\Jurusan,id',
			'kelas_id' => 'required|exists:App\Models\Kelas,id',
			'password' => 'required|string|max:50',
        ];
    }
}
