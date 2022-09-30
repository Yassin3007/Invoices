<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $count_all =Invoice::count();
        $count_invoices1 = Invoice::where('Value_Status', 1)->count();
        $count_invoices2 = Invoice::where('Value_Status', 2)->count();
        $count_invoices3 = Invoice::where('Value_Status', 3)->count();

        if($count_invoices2 == 0){
            $nspainvoices2=0;
        }
        else{
            $nspainvoices2 = $count_invoices2/ $count_all*100;
        }

        if($count_invoices1 == 0){
            $nspainvoices1=0;
        }
        else{
            $nspainvoices1 = $count_invoices1/ $count_all*100;
        }

        if($count_invoices3 == 0){
            $nspainvoices3=0;
        }
        else{
            $nspainvoices3 = $count_invoices3/ $count_all*100;
        }
        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 200, 'height' => 200])
            ->datasets([
                [
                    "label" => " اجمالي الفواتير ",
                    'backgroundColor' => ['#D07000'],
                    'data' => [100]
                ],
                [
                    "label" => "نسبة الفواتير المدفوعة",
                    'backgroundColor' => ['#A5F1E9'],
                    'data' => [$nspainvoices1]
                ],
                [
                    "label" => "نسبة الفواتير الغير مدفوعة",
                    'backgroundColor' => ['#EB1D36'],
                    'data' => [$nspainvoices2]
                ],
                [
                    "label" => " نسبة الفواتير المدفوعة جزئيا",
                    'backgroundColor' => ['#FFEEAF'],
                    'data' => [$nspainvoices3]
                ],


            ])
            ->options([]);

        $chartjs2 = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 200, 'height' => 285])
            ->labels([' الفواتير المدفوعة',' الفواتير الغير مدفوعة','الفواتير المدفوعة جزئيا'])

            ->datasets([
                [
                    'backgroundColor' => ['#A5F1E9', '#EB1D36','#FFEEAF'],
                    'hoverBackgroundColor' => ['#A5F1E9', '#EB1D36','#FFEEAF'],
                    'data' => [$nspainvoices1,$nspainvoices2,$nspainvoices3]
                ]
            ])
            ->options([]);

        return view('home', compact('chartjs','chartjs2'));
    }
}
