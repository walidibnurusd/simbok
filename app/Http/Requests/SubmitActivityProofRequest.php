<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitActivityProofRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
			'activity_id' => 'required|integer',
			'image' => 'image|mimes:png,jpg,jpeg|max:2048',
			'advice' => 'required|string',
			'value' => 'required|string',
		];
    }

	public function messages() {
		return [
			'image.required' => 'Upload foto bukti kegiatan',
			'advice.required' => 'Saran wajib diisi',
			'value.required' => 'Keterangan hasil kegiatan wajib diisi'
		];
	}
}
