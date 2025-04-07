<?php

namespace App\Http\Controllers;

use App\Models\sections;
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
        $sections = sections::all();
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

        $validator = Validator::make($request->all(), [
            'section_name' => 'required|string|max:255|unique:sections,section_name',
            'description' => 'required|string',
        ], [
            'section_name.required' => 'اسم القسم مطلوب.',
            'section_name.string' => 'اسم القسم يجب أن يكون نصًا.',
            'section_name.max' => 'اسم القسم يجب ألا يزيد عن 255 حرفًا.',
            'section_name.unique' => 'اسم القسم مستخدم من قبل.',

            'description.required' => 'الوصف مطلوب.',
            'description.string' => 'الوصف يجب أن يكون نصًا.',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        try {
            sections::create([
                'section_name' => $request->section_name,
                'description' => $request->description,
                'created_by' => Auth::user()->name,
            ]);

            session()->flash('success', 'تم إضافة القسم بنجاح');
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء إضافة القسم: ' . $e->getMessage());
        }

        return redirect('/sections');
    }


    /**
     * Display the specified resource.
     */
    public function show(sections $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sections $sections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;

        $this->validate($request, [

            'section_name' => 'required|max:255|unique:sections,section_name,' . $id,
            'description' => 'required',
        ], [

            'section_name.required' => 'يرجي ادخال اسم القسم',
            'section_name.unique' => 'اسم القسم مسجل مسبقا',
            'description.required' => 'يرجي ادخال البيان',

        ]);

        $sections = sections::find($id);
        $sections->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);

        session()->flash('success', 'تم تعديل القسم بنجاج');
        return redirect('/sections');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        sections::find($id)->delete();
        session()->flash('error', 'تم حذف القسم بنجاح');
        return redirect('/sections');
    }
}
