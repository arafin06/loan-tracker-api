<?php

namespace App\Http\Controllers;

use App\Enums\LoanStatus;
use App\Enums\LoanType;
use App\Http\Requests\StoreLoanApplicationRequest;
use App\Http\Requests\UpdateLoanApplicationRequest;
use App\Http\Requests\UpdateLoanStatusRequest;
use App\Http\Resources\LoanApplicationResource;
use App\Http\Resources\StatusHistoryResource;
use App\Models\LoanApplication;
use App\Models\LoanStatusHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LoanApplicationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = LoanApplication::with('user')
            ->byStatus($request->query('status'))
            ->byType($request->query('loan_type'))
            ->dateRange($request->query('from'), $request->query('to'))
            ->latest();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', "%{$search}%")
                  ->orWhere('applicant_email', 'like', "%{$search}%")
                  ->orWhere('property_address', 'like', "%{$search}%");
            });
        }

        $perPage = min((int) $request->query('per_page', 15), 50);

        return LoanApplicationResource::collection($query->paginate($perPage));
    }

    public function store(StoreLoanApplicationRequest $request): LoanApplicationResource
    {
        $loan = LoanApplication::create([
            ...$request->validated(),
            'user_id'     => $request->user()->id,
            'loan_status' => LoanStatus::Draft->value,
        ]);

        LoanStatusHistory::create([
            'loan_application_id' => $loan->id,
            'changed_by'          => $request->user()->id,
            'from_status'         => null,
            'to_status'           => LoanStatus::Draft->value,
            'notes'               => 'Application created.',
            'created_at'          => now(),
        ]);

        return new LoanApplicationResource(
            $loan->load(['user', 'statusHistories.changedBy'])
        );
    }

    public function show(LoanApplication $loanApplication): LoanApplicationResource
    {
        return new LoanApplicationResource(
            $loanApplication->load(['user', 'statusHistories.changedBy'])
        );
    }

    public function update(UpdateLoanApplicationRequest $request, LoanApplication $loanApplication): LoanApplicationResource
    {
        $loanApplication->update($request->validated());

        return new LoanApplicationResource(
            $loanApplication->load(['user', 'statusHistories.changedBy'])
        );
    }

    public function destroy(LoanApplication $loanApplication): JsonResponse
    {
        $loanApplication->delete();

        return response()->json(['message' => 'Loan application deleted.']);
    }

    public function updateStatus(UpdateLoanStatusRequest $request, LoanApplication $loanApplication): LoanApplicationResource
    {
        $fromStatus = $loanApplication->loan_status;
        $toStatus   = LoanStatus::from($request->status);

        $loanApplication->update(['loan_status' => $toStatus->value]);

        LoanStatusHistory::create([
            'loan_application_id' => $loanApplication->id,
            'changed_by'          => $request->user()->id,
            'from_status'         => $fromStatus->value,
            'to_status'           => $toStatus->value,
            'notes'               => $request->notes,
            'created_at'          => now(),
        ]);

        return new LoanApplicationResource(
            $loanApplication->load(['user', 'statusHistories.changedBy'])
        );
    }

    public function history(LoanApplication $loanApplication): AnonymousResourceCollection
    {
        return StatusHistoryResource::collection(
            $loanApplication->statusHistories()->with('changedBy')->get()
        );
    }

    public function stats(): JsonResponse
    {
        $byStatus = [];
        foreach (LoanStatus::cases() as $status) {
            $byStatus[$status->value] = [
                'label' => $status->label(),
                'color' => $status->color(),
                'count' => LoanApplication::where('loan_status', $status->value)->count(),
            ];
        }

        $byType = [];
        foreach (LoanType::cases() as $type) {
            $byType[$type->value] = [
                'label' => $type->label(),
                'count' => LoanApplication::where('loan_type', $type->value)->count(),
            ];
        }

        return response()->json([
            'total'        => LoanApplication::count(),
            'total_amount' => (float) LoanApplication::sum('loan_amount'),
            'by_status'    => $byStatus,
            'by_type'      => $byType,
        ]);
    }
}
