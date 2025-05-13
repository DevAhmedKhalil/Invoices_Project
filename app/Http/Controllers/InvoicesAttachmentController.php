<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicesAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoicesAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param $invoice_id
     */
    //! Store More Attachments
    public function store(Request $request, $invoice_id)
    {
        $invoice = Invoice::findOrFail($invoice_id);

        $request->validate([
            'file_name' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'file_name.mimes' => '* صيغة المرفق يجب ان تكون: pdf, jpg, jpeg, png',
            'file_name.max' => '* الحد الأقصى لحجم الملف هو 2 ميجابايت',
        ]);

        if ($request->hasFile('file_name')) {
            $file = $request->file('file_name');
            $file_name = $file->getClientOriginalName();

            $destinationPath = public_path('attachments/' . $invoice->invoice_number);

            // ✅ Check if a file already exists
            if (file_exists($destinationPath . '/' . $file_name)) {
                return back()->withErrors(['file_name' => 'يوجد ملف بنفس الاسم بالفعل. يرجى إعادة التسمية أو اختيار ملف مختلف.']);
            }

            // Create a folder if it doesn't exist
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Move the file
            $file->move($destinationPath, $file_name);

            // Save to a database
            InvoicesAttachment::create([
                'file_name' => $file_name,
                'invoice_number' => $invoice->invoice_number,
                'created_by' => Auth::user()->name,
                'invoice_id' => $invoice->id,
            ]);
        }

        session()->flash('success', 'تم إضافة المرفق بنجاح');

        return back();
    }


    /**
     * Display the specified resource.
     */
    public function show(InvoicesAttachment $invoicesAttachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvoicesAttachment $invoicesAttachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvoicesAttachment $invoicesAttachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoicesAttachment $invoicesAttachment)
    {
        //
    }
}
