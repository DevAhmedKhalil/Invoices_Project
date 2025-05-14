<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            // ------------------- المعرفات الأساسية -------------------
            $table->id();
            $table->string('invoice_number')->unique()->comment('رقم الفاتورة');
            $table->date('invoice_date')->comment('تاريخ الفاتورة');
            $table->date('due_date')->comment('تاريخ الاستحقاق');

            // ------------------- العلاقات مع الجداول الأخرى -------------------
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete()->comment('المنتج');
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete()->comment('القسم');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->comment('المستخدم');

            // ------------------- الحقول المالية -------------------
            $table->decimal('amount_collection', 10, 2)->default(0.00)->comment('مبلغ التحصيل');
            $table->decimal('amount_commission', 10, 2)->comment('مبلغ العمولة');
            $table->decimal('discount', 10, 2)->default(0.00)->comment('الخصم');
            $table->decimal('rate_vat', 5, 2)->comment('نسبة الضريبة');
            $table->decimal('value_vat', 10, 2)->comment('قيمة الضريبة');
            $table->decimal('total', 12, 2)->comment('الإجمالي شامل الضريبة');

            // ------------------- حالة الفاتورة -------------------
            $table->enum('status', ['paid', 'unpaid', 'partial', 'overdue'])
                ->default('unpaid')
                ->comment('حالة الدفع');
            $table->tinyInteger('status_value')
                ->comment('0=غير مدفوعة، 1=مدفوعة، 2=جزئية، 3=متأخرة');

            // ------------------- معلومات إضافية -------------------
            $table->text('note')->nullable()->comment('ملاحظات');
            $table->timestamps();
            $table->softDeletes();

            // ------------------- الفهارس -------------------
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