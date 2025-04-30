<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->date('due_date');

            // Relationships
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Financials
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('rate_vat', 5, 2);
            $table->decimal('value_vat', 10, 2);
            $table->decimal('total', 12, 2);

            // Status
            $table->enum('status', ['paid', 'unpaid', 'partial', 'overdue'])->default('unpaid');
            $table->tinyInteger('status_value')->comment('0=unpaid, 1=paid, 2=partial, 3=overdue');

            // Additional
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('invoice_number');
            $table->index('due_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};