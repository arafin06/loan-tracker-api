<?php

namespace App\Models;

use App\Enums\LoanStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanStatusHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'loan_application_id', 'changed_by',
        'from_status', 'to_status', 'notes', 'created_at',
    ];

    protected $casts = [
        'from_status' => LoanStatus::class,
        'to_status'   => LoanStatus::class,
        'created_at'  => 'datetime',
    ];

    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
