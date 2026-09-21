<?php

namespace App\Http\Requests;

use App\Enums\LoanType;
use App\Enums\PropertyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreLoanApplicationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'applicant_name'   => ['required', 'string', 'max:255'],
            'applicant_email'  => ['required', 'email', 'max:255'],
            'applicant_phone'  => ['required', 'string', 'max:20'],
            'loan_type'        => ['required', new Enum(LoanType::class)],
            'loan_amount'      => ['required', 'numeric', 'min:1000'],
            'property_address' => ['required', 'string', 'max:500'],
            'property_type'    => ['required', new Enum(PropertyType::class)],
            'ltv'              => ['nullable', 'numeric', 'min:0', 'max:100'],
            'noi'              => ['nullable', 'numeric', 'min:0'],
            'pitia'            => ['nullable', 'numeric', 'min:0'],
            'notes'            => ['nullable', 'string', 'max:5000'],
        ];
    }
}
