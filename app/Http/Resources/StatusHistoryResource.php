<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'from_status' => $this->from_status ? [
                'value' => $this->from_status->value,
                'label' => $this->from_status->label(),
                'color' => $this->from_status->color(),
            ] : null,
            'to_status'   => [
                'value' => $this->to_status->value,
                'label' => $this->to_status->label(),
                'color' => $this->to_status->color(),
            ],
            'notes'      => $this->notes,
            'changed_by' => [
                'id'   => $this->changedBy->id,
                'name' => $this->changedBy->name,
            ],
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
