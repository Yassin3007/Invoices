<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    function __construct()
    {

        $this->middleware('permission:الاقسام', ['only' => ['index']]);
        $this->middleware('permission:اضافة قسم', ['only' => ['create','store']]);
        $this->middleware('permission:تعديل قسم', ['only' => ['edit','update']]);
        $this->middleware('permission:حذف قسم', ['only' => ['destroy']]);

    }



    public function index()
    {
        $sections = Section::all();
        return view('sections.sections',compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SectionRequest $request)
    {
        $section = Section::create([
            'section_name'=>$request->section_name ,
            'description'=>$request->description ,
            'Created_by'=>Auth::user()->name
        ]);
        return redirect()->route('sections.index')->with(['success'=>'تم التخزين بنجاح']) ;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(SectionRequest $request)
    {
        $sec = Section::find($request->id);
        if (! $sec){
            return redirect()->route('sections.index')->with(['error'=>'هذا القسم غير موجود']);
        }
        $sec->update([
           'section_name'=>$request->section_name ,
           'description'=>$request->description
        ]);
        return redirect()->route('sections.index')->with(['success'=>'تم التعديل بنجاح']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $section = Section::find($request->id);
        if (! $section){
            return redirect()->route('sections.index')->with(['error'=>'هذا القسم غير موجود']);
        }
        $section->delete();
        return redirect()->route('sections.index')->with(['success'=>'تم الحذف بنجاح']);

    }
}
