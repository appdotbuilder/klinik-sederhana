<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrescriptionItemRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|integer|min:1',
            'dosage' => 'required|string|max:100',
            'frequency' => 'required|string|max:100',
            'duration' => 'required|string|max:100',
            'instructions' => 'nullable|string',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'inventory_item_id.required' => 'Obat wajib dipilih.',
            'inventory_item_id.exists' => 'Obat yang dipilih tidak valid.',
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.min' => 'Jumlah minimal 1.',
            'dosage.required' => 'Dosis wajib diisi.',
            'frequency.required' => 'Frekuensi wajib diisi.',
            'duration.required' => 'Durasi wajib diisi.',
        ];
    }
}