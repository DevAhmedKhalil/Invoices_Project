<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        return view('sections.sections', compact('sections'));
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
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'section_name' => 'required|string|max:255|unique:sections,section_name', // Make sure section_name is unique
            'description' => 'required|string',
        ], [
            'section_name.required' => 'اسم القسم مطلوب.',
            'section_name.string' => 'اسم القسم يجب أن يكون نصًا.',
            'section_name.max' => 'اسم القسم يجب ألا يزيد عن 255 حرفًا.',
            'section_name.unique' => 'اسم القسم مستخدم من قبل.',
            'description.required' => 'الوصف مطلوب.',
            'description.string' => 'الوصف يجب أن يكون نصًا.',
        ]);

        // If validation fails, return back with errors
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Try to create a new Section
        try {
            Section::create([
                'section_name' => $request->section_name,
                'description' => $request->description,
                'created_by' => Auth::user()->name, // Save the name of the authenticated user
            ]);

            // Show success message
            session()->flash('success', 'تم إضافة القسم بنجاح');
        } catch (\Exception $e) {
            // Handle any errors that occur
            session()->flash('error', 'حدث خطأ أثناء إضافة القسم: ' . $e->getMessage());
        }

        // Redirect to sections list
        return redirect('/section');
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return Section::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;

        // Validate the incoming request data
        $this->validate($request, [
            'section_name' => 'required|max:255|unique:sections,section_name,' . $id, // Ensure the section_name is unique except for the current record
            'description' => 'required',
        ], [
            'section_name.required' => 'يرجي ادخال اسم القسم',
            'section_name.unique' => 'اسم القسم مسجل مسبقا',
            'description.required' => 'يرجي ادخال البيان',
        ]);

        // Find the section by ID
        $section = Section::findOrFail($id);
        $section->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);

        // Flash success message
        session()->flash('success', 'تم تعديل القسم بنجاح');
        return redirect('/section');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;

        // Find and delete the section by ID
        $section = Section::find($id);
        $section->delete();

        // Flash success message after deletion
        session()->flash('success', 'تم حذف القسم بنجاح');
        return redirect('/section');
    }

}
