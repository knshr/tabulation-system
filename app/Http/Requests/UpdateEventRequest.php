<?php

namespace App\Http\Requests;

use App\Enums\EventStatus;
use App\Enums\ScoringMode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
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
            'venue' => ['nullable', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'status' => ['required', Rule::enum(EventStatus::class)],
            'scoring_mode' => ['required', Rule::enum(ScoringMode::class)],
        ];
    }
}
