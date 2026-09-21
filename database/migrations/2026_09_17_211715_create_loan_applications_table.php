<?php

use App\Enums\LoanStatus;
use App\Enums\LoanType;
use App\Enums\PropertyType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('applicant_name');
            $table->string('applicant_email');
            $table->string('applicant_phone', 20);

            $table->enum('loan_type', array_column(LoanType::cases(), 'value'));
            $table->decimal('loan_amount', 15, 2);
            $table->enum('loan_status', array_column(LoanStatus::cases(), 'value'))
                  ->default(LoanStatus::Draft->value);

            $table->string('property_address');
            $table->enum('property_type', array_column(PropertyType::cases(), 'value'));

            $table->decimal('ltv', 5, 2)->nullable()->comment('Loan-to-Value %');
            $table->decimal('noi', 15, 2)->nullable()->comment('Annual Net Operating Income');
            $table->decimal('pitia', 15, 2)->nullable()->comment('Monthly PITIA payment');

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('loan_status');
            $table->index('loan_type');
            $table->index('created_at');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};
