<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {

        $this->middleware('permission:المنتجات', ['only' => ['index']]);
        $this->middleware('permission:اضافة منتج', ['only' => ['create','store']]);
        $this->middleware('permission:تعديل منتج', ['only' => ['edit','update']]);
        $this->middleware('permission:حذف منتج', ['only' => ['destroy']]);

    }

    public function index()
    {
        $data = [];
        $data['sections'] = Section::all();
        $data['products'] = Product::all();
        return view('products.products',$data);
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
    public function store(ProductRequest $request)
    {



        $product = Product::create($request->except('_token'));

        return redirect()->route('products.index')->with(['success'=>'تم التخزين بنجاح']) ;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest  $request)
    {

        try {
            $product = Product::find($request->pro_id);
            $sec_id = Section::where('section_name',$request->section_name)->first()->id ;

            if(! $product){
                return redirect()->route('products.index')->with(['error'=>'هذا المنتج غير موجود']);
            }
            $product->update([
                'Product_name'=>$request->Product_name ,
                'description'=>$request->description ,
                'section_id'=>$sec_id
            ]);
            return redirect()->route('products.index')->with(['success'=>'تم التعديل بنجاح']);


        }catch (\Exception $ex){
            return redirect()->route('products.index')->with(['error'=>'حدث خطا ما ']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $product = Product::find($request->pro_id);
        if(! $product){
            return redirect()->route('products.index')->with(['error'=>'هذا المنتج غير موجود']);
        }

        $product->delete();

        return redirect()->route('products.index')->with(['success'=>'تم الحذف بنجاح']);


    }
}
