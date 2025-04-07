<?php /** @noinspection ALL */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('invoices', callback: function (Blueprint $table) {
            $table->increments('id'); // المعرف الرئيسي للفاتورة (رقم تلقائي)
            $table->string('invoice_number'); // رقم الفاتورة
            $table->date('invoice_date'); // تاريخ إصدار الفاتورة
            $table->date('due_date'); // تاريخ الاستحقاق
            $table->string('product'); // اسم المنتج أو الخدمة
            $table->string('section'); // القسم التابع له المنتج
            $table->string('discount'); // قيمة الخصم على الفاتورة
            $table->string('rate_vat'); // نسبة الضريبة المضافة (مثلاً 5%)
            $table->decimal('value_vat', 8, 2); // القيمة الفعلية للضريبة بعد الحساب
            $table->decimal('total', 8, 2); // إجمالي الفاتورة بعد الخصم والضريبة
            $table->string('status', 50); // حالة الفاتورة (مدفوعة، غير مدفوعة، مؤجلة)
            $table->integer('value_status'); // قيمة رقمية لحالة الفاتورة (مثلاً 1=مدفوعة)
            $table->text('note')->nullable(); // ملاحظات إضافية على الفاتورة (اختياري)
            $table->string('user'); // اسم المستخدم الذي أضاف الفاتورة
            $table->softDeletes(); // لحذف الفاتورة "نظريًا" بدون حذفها فعليًا من قاعدة البيانات
            $table->timestamps(); // تاريخ الإنشاء والتحديث التلقائي للسجل
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
