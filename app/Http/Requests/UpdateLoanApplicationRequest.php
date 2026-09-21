<?php

namespace App\Http\Requests;

use App\Enums\LoanType;
use App\Enums\PropertyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateLoanApplicationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'applicant_name'   => ['sometimes', 'string', 'max:255'],
            'applicant_email'  => ['sometimes', 'email', 'max:255'],
            'applicant_phone'  => ['sometimes', 'string', 'max:20'],
            'loan_type'        => ['sometimes', new Enum(LoanType::class)],
            'loan_amount'      => ['sometimes', 'numeric', 'min:1000'],
            'property_address' => ['sometimes', 'string', 'max:500'],
            'property_type'    => ['sometimes', new Enum(PropertyType::class)],
            'ltv'              => ['nullable', 'numeric', 'min:0', 'max:100'],
            'noi'              => ['nullable', 'numeric', 'min:0'],
            'pitia'            => ['nullable', 'numeric', 'min:0'],
            'notes'            => ['nullable', 'string', 'max:5000'],
        ];
    }
}
