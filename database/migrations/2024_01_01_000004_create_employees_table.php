<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('employee_code')->unique(); // NIK/Kode Karyawan
            $table->string('name');
            $table->string('position'); // Jabatan
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('join_date');
            $table->decimal('base_salary', 15, 2)->default(0);
            $table->enum('salary_type', ['monthly', 'daily', 'weekly'])->default('monthly');
            $table->enum('status', ['active', 'inactive', 'resigned'])->default('active');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Admin yang input
            $table->integer('month'); // 1-12
            $table->integer('year');
            $table->decimal('base_salary', 15, 2)->default(0);
            $table->decimal('allowance', 15, 2)->default(0); // Tunjangan
            $table->decimal('bonus', 15, 2)->default(0);
            $table->decimal('deduction', 15, 2)->default(0); // Potongan
            $table->decimal('total_salary', 15, 2)->default(0);
            $table->date('payment_date')->nullable();
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['employee_id', 'month', 'year']); // Satu gaji per bulan
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salaries');
        Schema::dropIfExists('employees');
    }
};
