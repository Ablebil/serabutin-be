<?php

namespace App\Http\Requests\Api\V1\Jobs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'budget_min' => ['required', 'numeric', 'min:0'],
            'budget_max' => ['required', 'numeric', 'min:0'],
            'workers_needed' => ['required', 'integer', 'min:1'],
            'location_district' => ['required', 'string', 'max:255'],
            'location_city' => ['required', 'string', 'in:Kota Malang,Kota Batu,Kabupaten Malang'],
            'start_at' => ['required', 'date'],
            'deadline_at' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.uuid' => 'Format kategori tidak valid.',
            'category_id.exists' => 'Kategori tidak ditemukan.',
            'title.required' => 'Judul pekerjaan wajib diisi.',
            'title.string' => 'Judul pekerjaan harus berupa teks.',
            'title.max' => 'Judul pekerjaan maksimal 255 karakter.',
            'description.required' => 'Deskripsi pekerjaan wajib diisi.',
            'description.string' => 'Deskripsi pekerjaan harus berupa teks.',
            'budget_min.required' => 'Budget minimal wajib diisi.',
            'budget_min.numeric' => 'Budget minimal harus berupa angka.',
            'budget_min.min' => 'Budget minimal tidak boleh negatif.',
            'budget_max.required' => 'Budget maksimal wajib diisi.',
            'budget_max.numeric' => 'Budget maksimal harus berupa angka.',
            'budget_max.min' => 'Budget maksimal tidak boleh negatif.',
            'workers_needed.required' => 'Jumlah pekerja wajib diisi.',
            'workers_needed.integer' => 'Jumlah pekerja harus berupa angka.',
            'workers_needed.min' => 'Jumlah pekerja minimal 1.',
            'location_district.required' => 'Kecamatan wajib diisi.',
            'location_district.string' => 'Kecamatan harus berupa teks.',
            'location_district.max' => 'Kecamatan maksimal 255 karakter.',
            'location_city.required' => 'Kota wajib diisi.',
            'location_city.string' => 'Kota harus berupa teks.',
            'location_city.in' => 'Kota tidak valid.',
            'start_at.required' => 'Waktu mulai wajib diisi.',
            'start_at.date' => 'Format waktu mulai tidak valid.',
            'deadline_at.required' => 'Deadline wajib diisi.',
            'deadline_at.date' => 'Format deadline tidak valid.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $budgetMin = $this->input('budget_min');
            $budgetMax = $this->input('budget_max');
            $startAt = $this->input('start_at');
            $deadlineAt = $this->input('deadline_at');

            if ($budgetMin && $budgetMax && $budgetMin > $budgetMax) {
                $validator->errors()->add('budget_min', 'Budget minimal tidak boleh lebih besar dari budget maksimal.');
            }

            if ($startAt && $deadlineAt && $startAt >= $deadlineAt) {
                $validator->errors()->add('start_at', 'Waktu mulai harus sebelum deadline.');
            }

            if ($startAt && now()->gt($startAt)) {
                $validator->errors()->add('start_at', 'Waktu mulai tidak boleh di masa lalu.');
            }

            if ($deadlineAt && now()->gt($deadlineAt)) {
                $validator->errors()->add('deadline_at', 'Deadline tidak boleh di masa lalu.');
            }
        });
    }
}
