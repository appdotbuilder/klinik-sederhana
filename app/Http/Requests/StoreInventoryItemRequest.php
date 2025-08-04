<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryItemRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:inventory_items,code',
            'type' => 'required|in:obat,barang_lain,alat_medis',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'minimal_alert' => 'required|integer|min:0',
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
            'name.required' => 'Nama item wajib diisi.',
            'code.required' => 'Kode item wajib diisi.',
            'code.unique' => 'Kode item sudah digunakan.',
            'type.required' => 'Jenis item wajib dipilih.',
            'type.in' => 'Jenis item harus salah satu dari: obat, barang_lain, alat_medis.',
            'unit.required' => 'Satuan wajib diisi.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.min' => 'Stok tidak boleh negatif.',
            'price.required' => 'Harga jual wajib diisi.',
            'price.min' => 'Harga jual tidak boleh negatif.',
            'minimal_alert.required' => 'Batas minimal alert wajib diisi.',
            'minimal_alert.min' => 'Batas minimal alert tidak boleh negatif.',
        ];
    }
}