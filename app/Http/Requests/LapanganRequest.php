<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LapanganRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'petugas';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $lapanganId = $this->route('lapangan') ? $this->route('lapangan')->id : null;
        
        return [
            'nama_lapangan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('lapangans', 'nama_lapangan')->ignore($lapanganId)
            ],
            'jenis' => [
                'required',
                'in:futsal,basket,badminton,tenis'
            ],
            'harga_per_jam' => [
                'required',
                'numeric',
                'min:1000',
                'max:999999999'
            ],
            'deskripsi' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'gambar' => [
                'nullable',
                'array',
                'max:5'
            ],
            'gambar.*' => [
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048' // 2MB
            ],
            'existing_images' => [
                'nullable',
                'array'
            ],
            'existing_images.*' => [
                'string'
            ],
            'is_active' => [
                'boolean'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nama_lapangan.required' => 'Nama lapangan harus diisi.',
            'nama_lapangan.unique' => 'Nama lapangan sudah digunakan.',
            'nama_lapangan.max' => 'Nama lapangan maksimal 255 karakter.',
            
            'jenis.required' => 'Jenis lapangan harus dipilih.',
            'jenis.in' => 'Jenis lapangan tidak valid.',
            
            'harga_per_jam.required' => 'Harga per jam harus diisi.',
            'harga_per_jam.numeric' => 'Harga per jam harus berupa angka.',
            'harga_per_jam.min' => 'Harga per jam minimal Rp 1.000.',
            'harga_per_jam.max' => 'Harga per jam terlalu besar.',
            
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',
            
            'gambar.array' => 'Format gambar tidak valid.',
            'gambar.max' => 'Maksimal 5 gambar yang dapat diupload.',
            
            'gambar.*.image' => 'File harus berupa gambar.',
            'gambar.*.mimes' => 'Gambar harus berformat: jpeg, png, jpg, gif, atau webp.',
            'gambar.*.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nama_lapangan' => 'nama lapangan',
            'jenis' => 'jenis lapangan',
            'harga_per_jam' => 'harga per jam',
            'deskripsi' => 'deskripsi',
            'gambar' => 'gambar',
            'gambar.*' => 'gambar',
            'is_active' => 'status aktif'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure is_active is boolean
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);

        // Clean harga_per_jam - remove any non-numeric characters
        if ($this->has('harga_per_jam')) {
            $harga = preg_replace('/[^0-9]/', '', $this->harga_per_jam);
            $this->merge([
                'harga_per_jam' => $harga ?: 0
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Custom validation for total images (existing + new)
            $existingImagesCount = count($this->input('existing_images', []));
            $newImagesCount = count($this->file('gambar', []));
            $totalImages = $existingImagesCount + $newImagesCount;
            
            if ($totalImages > 5) {
                $validator->errors()->add('gambar', 'Total gambar (existing + baru) tidak boleh lebih dari 5.');
            }
            
            // Validate that at least some basic info is provided
            if ($this->isMethod('post')) {
                if (empty($this->input('existing_images')) && empty($this->file('gambar'))) {
                    // Optional: Require at least one image
                    // $validator->errors()->add('gambar', 'Minimal satu gambar harus diupload.');
                }
            }
        });
    }
}