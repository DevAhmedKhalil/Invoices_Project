<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = sections::all();
        $products = products::all();
        return view('products.products', compact('sections', 'products'));
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
        // 1- Validate the incoming request data.
        $validatedData = $request->validate([
            'product_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->where(function ($query) use ($request) {
                    return $query->where('section_id', $request->section_id);
                }),
            ],
            'section_id' => 'required|exists:sections,id',
            'description' => 'required|string',
        ], [
            'product_name.required' => 'يرجي ادخال اسم المنتج',
            'product_name.unique' => 'اسم المنتج مسجل مسبقًا في هذا القسم',
            'description.required' => 'يرجي ادخال البيان',
        ]);


        try {
            // 2- Create a new product using the validated data.
            products::create([
                'product_name' => $validatedData['product_name'],
                'section_id' => $validatedData['section_id'],
                'description' => $validatedData['description'] ?? null,
            ]);

            // 3- Show a success message in Arabic.
            Session::flash('success', 'تم إضافة المنتج بنجاح');
        } catch (\Exception $e) {
            // If an error occurs, flash an error message.
            Session::flash('error', 'حدث خطأ أثناء إضافة المنتج، يرجى المحاولة لاحقاً');
        }

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
    public function update(Request $request, $id)
    {
        // 1- Validation
        $validated = $request->validate([
            'product_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->where(function ($query) use ($request) {
                    return $query->where('section_id', $request->section_id);
                })->ignore($id),
            ],
            'section_id' => 'required|exists:sections,id',
            'description' => 'required|string',
        ], [
            'product_name.required' => 'يرجي ادخال اسم المنتج',
            'product_name.unique' => 'اسم المنتج مسجل مسبقًا في هذا القسم',
            'description.required' => 'يرجي ادخال البيان',
        ]);

        // 2- Find the specific product to update
        $product = products::findOrFail($id);

        try {
            // 3- Update with validated data
            $product->update($validated);

            // 4- Show success message
            Session::flash('success', 'تم تحديث المنتج بنجاح');

        } catch (\Exception $e) {
            // If an error occurs, flash an error message.
            Session::flash('error', 'حدث خطأ أثناء إضافة المنتج، يرجى المحاولة لاحقاً');
        }

        return redirect('/products');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = products::findOrFail($id);

        try {
            $product->delete();

            Session::flash('success', 'تم حذف المنتج بنجاح');

        } catch (\Exception $e) {
            // If an error occurs, flash an error message.
            Session::flash('error', 'حدث خطأ أثناء إضافة المنتج، يرجى المحاولة لاحقاً');
        }

        return redirect()->back();
    }
}
