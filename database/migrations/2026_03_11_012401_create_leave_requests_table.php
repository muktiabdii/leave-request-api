<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('employee_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            $table->date('start_date');
            $table->date('end_date');
            
            $table->integer('total_days');

            $table->text('reason')->nullable();

            $table->string('attachment')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');

            $table->text('admin_note')->nullable();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
