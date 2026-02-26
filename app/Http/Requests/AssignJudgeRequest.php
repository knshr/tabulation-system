<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignJudgeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judge_id' => ['required', 'exists:users,id'],
        ];
    }
}
