<?php

namespace App\Http\Controllers;

use App\Exports\InvoiceExport;
use App\Models\Invoice;
use App\Models\Invoice_attachments;
use App\Models\Invoice_details;
use App\Models\Section;
use App\Models\User;
use App\Notifications\AddInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.invoices',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.add_invoice',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

            Invoice::create([
                'invoice_number' => $request->invoice_number,
                'invoice_Date' => $request->invoice_Date,
                'Due_date' => $request->Due_date,
                'product' => $request->product,
                'section_id' => $request->Section,
                'Amount_collection' => $request->Amount_collection,
                'Amount_Commission' => $request->Amount_Commission,
                'Discount' => $request->Discount,
                'Value_VAT' => $request->Value_VAT,
                'Rate_VAT' => $request->Rate_VAT,
                'Total' => $request->Total,
                'Status' => 'غير مدفوعة',
                'Value_Status' => 2,
                'note' => $request->note,

            ]);

            $invoice_id = Invoice::latest()->first()->id ;
            Invoice_details::create([
                'id_Invoice' => $invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => 'غير مدفوعة',
                'Value_Status' => 2,
                'note' => $request->note,
                'user' => (Auth::user()->name),
            ]);

            if ($request->has('pic')){
                $invoice_id = Invoice::latest()->first()->id ;
                $invoice_number =$request->invoice_number ;
                $image = $request->file('pic');
                $file_name = $image->getClientOriginalName();

                $attachments= new Invoice_attachments();
                $attachments->file_name = $file_name;
                $attachments->invoice_number = $invoice_number;
                $attachments->Created_by = Auth::user()->name;
                $attachments->invoice_id = $invoice_id;
                $attachments->save();

                $image_name= $request->pic->getClientOriginalName();
                $request->pic->move(public_path('Attachments/' . $invoice_number), $image_name);
            }



            return redirect()->route('invoices.index')->with(['success'=>'تم التخزين بنجاح']) ;



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoices = Invoice::find($id);

        return view('invoices.status_update',compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoices = Invoice::findOrFail($id);
        $sections = Section::all();
        return view('invoices.edit_invoices',compact('invoices','sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $invoices = Invoice::findOrFail($request->invoice_id);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);

        return redirect()->route('invoices.index')->with(['success'=>'تم التعديل بنجاح']) ;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $id = $request->invoice_id;

        $invoices = Invoice::where('id',$id)->first();

        $Details = Invoice_attachments::where('invoice_id', $id)->first();

        $id_page =$request->id_page;


        if (!$id_page==2) {

            if (!empty($Details->invoice_number)) {

                Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
            }


            $invoices->forceDelete();
            return redirect()->route('invoices.index')->with(['success'=>'تم الحذف بنجاح']);
        }

        else {

            $invoices->delete();
            return redirect()->route('invoices.index')->with(['success'=>'تمت الارشفة بنجاح']);
        }


    }

    public function getProducts($id){
        $products= DB::table('products')->where('section_id',$id)->pluck('Product_name','id');

        return json_encode($products);
    }

    public function open_file($invoice_number,$file_name){
        $files= Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number.'/'. $file_name);
        return response()->file($files);

    }

    public function get_file($invoice_number,$file_name){
        $files= Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number.'/'. $file_name);
        return response()->download($files);

    }

    public function Status_Update($id ,Request $request){

        $invoices = Invoice::findOrFail($id);

        if ($request->Status === 'مدفوعة') {

            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            Invoice_details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }

        else {
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            Invoice_details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }

        return redirect()->route('invoices.index')->with(['success'=>'تم التعديل بنجاح']) ;

    }

    public function Invoice_Paid(){
        $invoices = Invoice::where('value_status',1)->get();
        return view('invoices.invoices',compact('invoices'));
    }

    public function Invoice_UnPaid(){
        $invoices = Invoice::where('value_status',2)->get();
        return view('invoices.invoices',compact('invoices'));
    }

    public function Invoice_Partial(){
        $invoices = Invoice::where('value_status',3)->get();
        return view('invoices.invoices',compact('invoices'));
    }

    public function Print_invoice($id){
        $invoices = Invoice::find($id);
        return view('invoices.print_invoice',compact('invoices'));
    }

    public function export()
    {

        return Excel::download(new InvoiceExport, 'users.xlsx');

    }
}
