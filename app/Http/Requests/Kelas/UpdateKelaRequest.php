<?php

namespace App\Http\Requests\Kelas;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKelaRequest extends FormRequest
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
            'nama_kelas' => 'required|string|max:255',
        ];
    }
}
