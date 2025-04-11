<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = sections::all();
        return view('products.products', compact('sections'));
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
        // Validate the incoming request data.
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'section_id' => 'required|exists:sections,id',
            'description' => 'nullable|string',
        ]);

        try {
            // Create a new product using the validated data.
            products::create([
                'product_name' => $validatedData['product_name'],
                'section_id' => $validatedData['section_id'],
                'description' => $validatedData['description'] ?? null,
            ]);

            // Flash a success message in Arabic.
            Session::flash('success', 'تم إضافة المنتج بنجاح');
        } catch (\Exception $e) {
            // If an error occurs, flash an error message.
            Session::flash('error', 'حدث خطأ أثناء إضافة المنتج، يرجى المحاولة لاحقاً');
        }

        // Redirect back to the same page.
        return redirect('/products');
    }

    /**
     * Display the specified resource.
     */
    public function show(products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, products $products)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(products $products)
    {
        //
    }
}
