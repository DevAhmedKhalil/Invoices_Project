<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
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
//        echo "Hello World";
        return view('invoices.invoices');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
//        $section = section::all();
//        $product = product::all();

        // Use eager loading to optimize queries
        $sections = Section::with('product')->get(); // Assuming Section model has product relationship

        return view('invoices.add_invoice', compact('sections'));
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
