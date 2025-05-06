<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicesAttachment;
use App\Models\InvoicesDetails;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            \App\Models\InvoicesAttachment::create([
                'file_name' => $file_name,
                'invoice_number' => $invoice->invoice_number,
                'created_by' => Auth::user()->name,
                'invoice_id' => $invoice->id,
            ]);
        }

        // Flash success message to the session and redirect
        session()->flash('success', 'Invoice added successfully');
        return redirect("/invoice");
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoices)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoices)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoices)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoices)
    {
        //
    }
}
