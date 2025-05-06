<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicesAttachment;
use App\Models\InvoicesDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{

    public function viewFile(string $invoice_number, string $file_name)
    {
        $path = Storage::disk('public-uploads')->path("{$invoice_number}/{$file_name}");

        if (!file_exists($path)) {
            abort(404, 'الملف غير موجود');
        }

        return response()->file($path);
    }

    public function downloadFile(string $invoice_number, string $file_name)
    {
        $path = Storage::disk('public-uploads')->path("{$invoice_number}/{$file_name}");

        if (!file_exists($path)) {
            abort(404, 'الملف غير موجود');
        }

        return response()->download($path);
    }

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
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoicesDetails $invoices_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $invoices = Invoice::where('id', $id)->first();
        $details = InvoicesDetails::where('invoice_id', $id)->get();
        $attachments = InvoicesAttachment::where('invoice_id', $id)->get();

        return view('invoices.invoices_details', compact('invoices', 'details', 'attachments', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvoicesDetails $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, InvoicesDetails $invoices_details)
    {
        $attachment = InvoicesAttachment::findOrFail($request->id_file);
        $attachment->delete();

//        Storage::disk('public-uploads')->deleteDirectory($request->invoice_number);
//        // sometimes invoice can have a lot of attachment
        Storage::disk('public-uploads')->delete("{$request->invoice_number}/{$attachment->file_name}");
        session()->flash('delete', 'تم حزف المرفق بنجاج');

        return redirect()->back();
    }
}
