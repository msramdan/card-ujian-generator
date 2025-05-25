<?php

namespace App\Http\Requests\RuangUjians;

use Illuminate\Foundation\Http\FormRequest;

class StoreRuangUjianRequest extends FormRequest
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
            'nama_ruang_ujian' => 'required|string|max:255',
        ];
    }
}
