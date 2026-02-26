<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCriteriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'max_score' => ['required', 'numeric', 'min:0.01'],
            'percentage_weight' => ['required', 'numeric', 'min:0.01', 'max:100'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
