<?php

namespace App\Http\Requests\Api\V1\Jobs;

use App\Models\Job;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'uuid', 'exists:categories,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'budget_min' => ['sometimes', 'numeric', 'min:0'],
            'budget_max' => ['sometimes', 'numeric', 'min:0'],
            'workers_needed' => ['sometimes', 'integer', 'min:1'],
            'location_district' => ['sometimes', 'string', 'max:255'],
            'location_city' => ['sometimes', 'string', 'in:Kota Malang,Kota Batu,Kabupaten Malang'],
            'start_at' => ['sometimes', 'date'],
            'deadline_at' => ['sometimes', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.uuid' => 'Format kategori tidak valid.',
            'category_id.exists' => 'Kategori tidak ditemukan.',
            'title.string' => 'Judul pekerjaan harus berupa teks.',
            'title.max' => 'Judul pekerjaan maksimal 255 karakter.',
            'description.string' => 'Deskripsi pekerjaan harus berupa teks.',
            'budget_min.numeric' => 'Budget minimal harus berupa angka.',
            'budget_min.min' => 'Budget minimal tidak boleh negatif.',
            'budget_max.numeric' => 'Budget maksimal harus berupa angka.',
            'budget_max.min' => 'Budget maksimal tidak boleh negatif.',
            'workers_needed.integer' => 'Jumlah pekerja harus berupa angka.',
            'workers_needed.min' => 'Jumlah pekerja minimal 1.',
            'location_district.string' => 'Kecamatan harus berupa teks.',
            'location_district.max' => 'Kecamatan maksimal 255 karakter.',
            'location_city.string' => 'Kota harus berupa teks.',
            'location_city.in' => 'Kota tidak valid.',
            'start_at.date' => 'Format waktu mulai tidak valid.',
            'deadline_at.date' => 'Format deadline tidak valid.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $job = $this->route('id') ? Job::find($this->route('id')) : null;
            $budgetMin = $this->input('budget_min');
            $budgetMax = $this->input('budget_max');
            $startAt = $this->input('start_at');
            $deadlineAt = $this->input('deadline_at');

            if ($job && $this->has('workers_needed') && $job->hasAcceptedBids()) {
                $validator->errors()->add('workers_needed', 'Jumlah pekerja tidak bisa diubah jika sudah ada bid yang diterima.');
            }

            $finalBudgetMin = $budgetMin ?? $job?->budget_min;
            $finalBudgetMax = $budgetMax ?? $job?->budget_max;

            if ($finalBudgetMin && $finalBudgetMax && $finalBudgetMin > $finalBudgetMax) {
                $validator->errors()->add('budget_min', 'Budget minimal tidak boleh lebih besar dari budget maksimal.');
            }

            $finalStartAt = $startAt ?? $job?->start_at;
            $finalDeadlineAt = $deadlineAt ?? $job?->deadline_at;

            if ($finalStartAt && $finalDeadlineAt && $finalStartAt >= $finalDeadlineAt) {
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
