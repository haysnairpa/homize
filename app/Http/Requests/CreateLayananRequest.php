<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLayananRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama_layanan' => 'required|string|max:255',
            'deskripsi_layanan' => 'required|string',
            'pengalaman' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|in:kg,unit,pcs',
            'durasi' => 'required|integer|min:1',
            'tipe_durasi' => 'required|in:Jam,Hari,Pertemuan',
            'jam_operasional.hari' => 'required|integer|between:1,7',
            'jam_operasional.jam_buka' => 'required|date_format:H:i',
            'jam_operasional.jam_tutup' => 'required|date_format:H:i',
            'aset_layanan.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sertifikasi.*.nama' => 'nullable|string',
            'sertifikasi.*.file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'revisi_harga' => 'nullable|numeric|min:0',
            'revisi_durasi' => 'nullable|integer|min:1',
            'revisi_tipe_durasi' => 'nullable|in:Jam,Hari,Pertemuan',
        ];
    }

    public function messages()
    {
        return [
            'jam_operasional.hari.required' => 'Hari operasional harus dipilih',
            'jam_operasional.jam_buka.required' => 'Jam buka harus diisi',
            'jam_operasional.jam_tutup.required' => 'Jam tutup harus diisi',
            'satuan.in' => 'Satuan harus berupa kg, unit, atau pcs',
        ];
    }
}