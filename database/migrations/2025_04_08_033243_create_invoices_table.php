<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id(); // Shortened form of bigIncrements
            $table->string('invoice_number')->unique(); // Ensure unique invoice numbers
            $table->date('invoice_date');
            $table->date('due_date');
            $table->string('product', 255);

            // Relationship with sections table
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');

            // Financial columns (using decimal for precise calculations)
            $table->decimal('discount', 8, 2)->default(0.00);
            $table->decimal('rate_vat', 5, 2); // Stores values like 5.00 for 5%
            $table->decimal('value_vat', 10, 2);
            $table->decimal('total', 10, 2);

            // Payment status handling
            $table->enum('status', ['paid', 'unpaid', 'partial', 'overdue'])->default('unpaid');
            $table->tinyInteger('value_status')->comment('0=unpaid, 1=paid, 2=partial, 3=overdue');

            // User relationship
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Additional fields
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('invoice_number');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};