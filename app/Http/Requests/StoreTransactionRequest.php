<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'name'          => ['nullable', 'string', 'min:3'],
            'category_id'   => ['required', 'exists:categories,id'],
            'account_id'    => ['required', 'exists:accounts,id'],
            'to_account_id' => ['nullable', 'required_if:type,transfer', 'exists:accounts,id', 'different:account_id'],
            'amount'        => ['required', 'numeric', 'min:1'],
            'type'          => ['required', 'in:income,expense,transfer'],
            'date'          => ['required', 'date'],
        ];
    }

    /**
     * Pesan validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'amount.required'           => 'Jumlah tidak boleh kosong.',
            'amount.numeric'            => 'Jumlah harus berupa angka.',
            'amount.min'                => 'Jumlah minimal Rp 1.',
            'type.required'             => 'Silakan pilih jenis transaksi (Pemasukan, Pengeluaran, atau Transfer).',
            'type.in'                   => 'Jenis transaksi tidak valid.',
            'date.required'             => 'Tanggal tidak boleh kosong.',
            'date.date'                 => 'Format tanggal tidak valid.',
            'name.min'                  => 'Nama minimal 3 karakter.',
            'category_id.required'      => 'Kategori tidak boleh kosong.',
            'category_id.exists'        => 'Kategori yang dipilih tidak ditemukan.',
            'account_id.required'       => 'Rekening tidak boleh kosong.',
            'account_id.exists'         => 'Rekening yang dipilih tidak ditemukan.',
            'to_account_id.required_if' => 'Rekening tujuan wajib diisi untuk transfer.',
            'to_account_id.exists'      => 'Rekening tujuan tidak ditemukan.',
            'to_account_id.different'   => 'Rekening tujuan harus berbeda dari rekening sumber.',
        ];
    }

    /**
     * Siapkan data untuk validasi — generate default name jika kosong.
     */
    protected function prepareForValidation(): void
    {
        if (empty($this->name)) {
            $typeLabels = [
                'income'   => 'Pemasukan',
                'expense'  => 'Pengeluaran',
                'transfer' => 'Transfer',
            ];
            $label = $typeLabels[$this->type] ?? 'Transaksi';
            $this->merge([
                'name' => $label . ' ' . now()->translatedFormat('d M'),
            ]);
        }
    }
}
