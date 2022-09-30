<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Invoice_attachments;
use App\Models\Invoice_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice_details  $invoice_details
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice_details $invoice_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice_details  $invoice_details
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['invoices'] = Invoice::find($id);
        $data['details']= Invoice_details::where('id_invoice',$id)->get();
        $data['attachments']=Invoice_attachments::where('invoice_id',$id)->get();
        return view('invoices.details_invoice',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice_details  $invoice_details
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice_details $invoice_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice_details  $invoice_details
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $attach = Invoice_attachments::find($request->id_file);
        $attach->delete() ;
        Storage::disk('public_uploads')->delete($request->invoice_number.'/'.$request->file_name);

        return redirect()->back()->with(['success'=>'تم الحذف بنجاح']);

    }
}
