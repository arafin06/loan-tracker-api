<?php

namespace App\Models;

use App\Enums\LoanStatus;
use App\Enums\LoanType;
use App\Enums\PropertyType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'applicant_name', 'applicant_email', 'applicant_phone',
        'loan_type', 'loan_amount', 'loan_status',
        'property_address', 'property_type',
        'ltv', 'noi', 'pitia', 'notes',
    ];

    protected $casts = [
        'loan_type'     => LoanType::class,
        'loan_status'   => LoanStatus::class,
        'property_type' => PropertyType::class,
        'loan_amount'   => 'decimal:2',
        'ltv'           => 'decimal:2',
        'noi'           => 'decimal:2',
        'pitia'         => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(LoanStatusHistory::class)->latest('created_at');
    }

    public function scopeByStatus($query, ?string $status)
    {
        return $status ? $query->where('loan_status', $status) : $query;
    }

    public function scopeByType($query, ?string $type)
    {
        return $type ? $query->where('loan_type', $type) : $query;
    }

    public function scopeDateRange($query, ?string $from, ?string $to)
    {
        if ($from) $query->whereDate('created_at', '>=', $from);
        if ($to)   $query->whereDate('created_at', '<=', $to);
        return $query;
    }
}
