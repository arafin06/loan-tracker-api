<?php

namespace App\Http\Requests;

use App\Enums\LoanStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateLoanStatusRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(LoanStatus::class)],
            'notes'  => ['nullable', 'string', 'max:1000'],
        ];
    }
}
