<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'applicant_name'   => $this->applicant_name,
            'applicant_email'  => $this->applicant_email,
            'applicant_phone'  => $this->applicant_phone,
            'loan_type'        => [
                'value' => $this->loan_type->value,
                'label' => $this->loan_type->label(),
            ],
            'loan_amount'      => (float) $this->loan_amount,
            'loan_status'      => [
                'value' => $this->loan_status->value,
                'label' => $this->loan_status->label(),
                'color' => $this->loan_status->color(),
            ],
            'property_address' => $this->property_address,
            'property_type'    => $this->property_type->value,
            'ltv'              => $this->ltv    ? (float) $this->ltv    : null,
            'noi'              => $this->noi    ? (float) $this->noi    : null,
            'pitia'            => $this->pitia  ? (float) $this->pitia  : null,
            'notes'            => $this->notes,
            'created_by'       => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ],
            'created_at'       => $this->created_at->toISOString(),
            'updated_at'       => $this->updated_at->toISOString(),
            'status_histories' => StatusHistoryResource::collection(
                $this->whenLoaded('statusHistories')
            ),
        ];
    }
}
