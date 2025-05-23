<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicesAttachment;
use App\Models\InvoicesDetails;
use App\Models\Product;
use App\Models\Section;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class InvoicesController extends Controller
{

    public function getProducts($id)
    {
        // Return product for a specific section via AJAX (used in dynamic dropdowns)
        $products = DB::table("products")->where("section_id", $id)->pluck("proruct_name", "id");
        return json_encode($products);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
//        $section = section::all();
//        $product = product::all();

        // Use eager loading to optimize queries
        $sections = Section::with('product')->get(); // Assuming a Section model has a product relationship

        return view('invoices.add_invoice', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'section_id' => 'required|exists:sections,id',
            'product_id' => 'required|exists:products,id',
            'amount_collection' => 'nullable|numeric',
            'amount_commission' => 'required|numeric',
            'discount' => 'required|numeric',
            'rate_vat' => 'required|string',
            'value_vat' => 'required|numeric',
            'total' => 'required|numeric',
            'note' => 'nullable|string',
            'pic' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Remove % sign and convert VAT rate to float
        $rate_vat = rtrim($validatedData['rate_vat'], '%');
        $rate_vat = (float)$rate_vat;

        // Create the invoice record in the database
        $invoice = Invoice::create([
            'invoice_number' => $validatedData['invoice_number'],
            'invoice_date' => $validatedData['invoice_date'],
            'due_date' => $validatedData['due_date'],
            'section_id' => $validatedData['section_id'],
            'product_id' => $validatedData['product_id'],
            'amount_collection' => $validatedData['amount_collection'],
            'amount_commission' => $validatedData['amount_commission'],
            'discount' => $validatedData['discount'],
            'rate_vat' => $rate_vat,
            'value_vat' => $validatedData['value_vat'],
            'total' => $validatedData['total'],
            'note' => $validatedData['note'],
            'user_id' => Auth::id(),
            'status' => 'unpaid',
            'status_value' => 0, // 0 = unpaid
        ]);

        // Save invoice details in the related table
        InvoicesDetails::create([
            'invoice_id' => $invoice->id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product_id,
            'section' => $request->section_id,
            'status' => 'unpaid',
            'status_value' => 0,
            'note' => $request->note,
            'user' => Auth::user()->name,
        ]);

        // Handle file attachment if provided
        if ($request->hasFile('pic')) {
            $file = $request->file('pic');
            $file_name = $file->getClientOriginalName();

            // Move the file to the public attachments directory
            $file->move(public_path('attachments/' . $invoice->invoice_number), $file_name);

            // Store file name in the database using the InvoicesAttachment model
            InvoicesAttachment::create([
                'file_name' => $file_name,
                'invoice_number' => $invoice->invoice_number,
                'created_by' => Auth::user()->name,
                'invoice_id' => $invoice->id,
            ]);
        }

        // Flash success message to the session and redirect
        return redirect("/invoice")->with([
            'notif' => [
                'msg' => 'تم إضافة الفاتورة بنجاح',
                'type' => 'success'
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoice = Invoice::with('details')->findOrFail($id);
        $details = $invoice->details;

        return view('invoices.print_invoice', compact('invoice', 'details'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice = Invoice::with(['section', 'product'])->findOrFail($id);
        $sections = Section::all();
        $products = Product::all();

        return view('invoices.edit_invoice', compact('invoice', 'sections', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // البحث عن الفاتورة
            $invoice = Invoice::findOrFail($id);

            // استخدام القيم الحالية كقيم افتراضية
            $request->merge([
                'section_id' => $request->section_id ?? $invoice->section_id,
                'product_id' => $request->product_id ?? $invoice->product_id
            ]);

            // التحقق من صحة البيانات
            $validatedData = $request->validate([
                'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number,' . $id,
                'invoice_date' => 'required|date',
                'due_date' => 'required|date|after_or_equal:invoice_date',
                'section_id' => 'required|exists:sections,id',
                'product_id' => 'required|exists:products,id',
                'amount_collection' => 'nullable|numeric',
                'amount_commission' => 'required|numeric',
                'discount' => 'required|numeric',
                'rate_vat' => 'required|string',
                'value_vat' => 'required|numeric',
                'total' => 'required|numeric',
            ]);

            // تحويل نسبة الضريبة من نص إلى رقم
            $rate_vat = (float)str_replace('%', '', $request->rate_vat);

            // تحديث كافة الحقول مباشرة من الـ Request
            $invoice->update([
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'section_id' => $request->section_id,
                'product_id' => $request->product_id,
                'amount_collection' => $request->amount_collection,
                'amount_commission' => $request->amount_commission,
                'discount' => $request->discount,
                'rate_vat' => $rate_vat,
                'value_vat' => $request->value_vat,
                'total' => $request->total,
                'note' => $request->note,
            ]);

            // تسجيل التغييرات في جدول التفاصيل
//            InvoicesDetails::create([
//                'invoice_id' => $invoice->id,
//                'invoice_number' => $invoice->invoice_number,
//                'product' => $request->product_id,
//                'section' => $request->section_id,
//                'status' => $invoice->status,
//                'status_value' => $invoice->status_value,
//                'note' => $request->note,
//                'user' => Auth::user()->name,
//            ]);

            return redirect()->route('invoice.index')->with([
                'notif' => [
                    'msg' => 'تم تحديث الفاتورة بنجاح',
                    'type' => 'success'
                ]
            ]);

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء التحديث: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Step 1: Find the invoice by ID
        $invoice = Invoice::find($id);

        // Step 2: Check if the invoice exists
        if (!$invoice) {
            return redirect()->back()->with('error', 'الفاتورة غير موجودة.');
        }

        // Step 3: Soft delete the invoice (sets deleted_at timestamp)
        $invoice->delete();

        // Step 4: Redirect back with a success notification
        return redirect()->route('invoice.index')->with([
            'notif' => [
                'msg' => 'تم أرشفة الفاتورة بنجاح',
                'type' => 'success'
            ]
        ]);
    }

    public function forceDestroy($id)
    {
        // Step 1: Find the invoice, including soft-deleted ones and its attachments
        $invoice = Invoice::withTrashed()->with('attachments')->find($id);

        // Step 2: Check if the invoice exists
        if (!$invoice) {
            return redirect()->back()->with('error', 'الفاتورة غير موجودة.');
        }

        // Step 3: Delete all related attachments from the database
        $invoice->attachments()->delete(); // this deletes from DB

        // Step 4: Delete the full invoice attachments directory from storage
        $folderPath = public_path('attachments/' . $invoice->invoice_number);
        if (File::exists($folderPath)) {
            File::deleteDirectory($folderPath);
        }

        // Step 5: Permanently delete the invoice from the database
        $invoice->forceDelete();

        // Step 6: Redirect with success message
        return redirect()->route('invoice.index')->with([
            'notif' => [
                'msg' => 'تم حذف الفاتورة والمرفقات نهائيًا',
                'type' => 'error'
            ]
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        // Determine the numeric value based on the status text
        $status = $request->status;
        $status_value = match ($status) {
            'unpaid' => 0,
            'paid' => 1,
            'partial' => 2,
            'overdue' => 3,
            default => 0,
        };

        // Update invoice main status and value
        $invoice->status = $status;
        $invoice->status_value = $status_value;
        $invoice->updated_at = now();
        $invoice->save();

        // Create a new record in the invoice details table for tracking
        InvoicesDetails::create([
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'product' => $invoice->product->product_name ?? 'Unknown',
            'section' => $invoice->section->section_name ?? 'Unknown',
            'status' => $status,
            'status_value' => $status_value,
            'payment_date' => $status === 'paid' ? now()->toDateString() : null,
            'note' => $request->note ?? null,
            'user' => auth()->user()->name,
        ]);

        return redirect()->back()->with([
            'notif' => [
                'msg' => 'تم تحديث حالة الفاتورة وتسجيل التفاصيل بنجاح',
                'type' => 'success'
            ]
        ]);
    }

    public function invoicePaid()
    {
        $invoices = Invoice::where('status_value', 1)->get();
        return view('invoices.invoicesPaid', compact('invoices'));
    }

    public function invoiceUnpaid()
    {
        $invoices = Invoice::where('status_value', 0)->get();
        return view('invoices.invoicesUnpaid', compact('invoices'));
    }

    public function invoicePartial()
    {
        $invoices = Invoice::where('status_value', 2)->get();
        return view('invoices.invoicesPartial', compact('invoices'));
    }

    public function invoiceArchived()
    {
        $invoices = Invoice::onlyTrashed()->get();
        return view('invoices.invoicesArchived', compact('invoices'));
    }

    public function restore(string $id)
    {
        $invoice = Invoice::onlyTrashed()->findOrFail($id);
        $invoice->restore();

        return redirect()->route('invoice.archived')->with([
            'notif' => [
                'msg' => 'تم استعادة الفاتورة بنجاح.',
                'type' => 'success'
            ]
        ]);
    }

    public function print($id)
    {
        $invoice = Invoice::with('details', 'attachments')->findOrFail($id);
        return view('invoices.print_invoice', compact('invoice'));
    }


}
