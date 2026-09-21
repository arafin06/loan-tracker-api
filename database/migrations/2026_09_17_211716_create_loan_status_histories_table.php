<?php

use App\Enums\LoanStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('changed_by')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->enum('from_status', array_column(LoanStatus::cases(), 'value'))->nullable();
            $table->enum('to_status', array_column(LoanStatus::cases(), 'value'));
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_status_histories');
    }
};
