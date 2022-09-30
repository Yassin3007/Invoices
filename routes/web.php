<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('CheckActive');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('invoices','InvoiceController');

Route::resource('sections','SectionController');

Route::resource('products','ProductController');

Route::get('section/{id}','InvoiceController@getProducts');



Route::get('InvoicesDetails/{id}','InvoiceDetailsController@edit');

Route::resource('InvoiceAttachments','InvoiceAttachmentsController');

Route::get('View_file/{invoice_number}/{file_name}','InvoiceController@open_file');

Route::get('download/{invoice_number}/{file_name}','InvoiceController@get_file');

Route::post('delete','InvoiceDetailsController@destroy')->name('delete_file');

Route::get('Status_show/{id}','InvoiceController@show')->name('Status_show');

Route::post('/Status_Update/{id}', 'InvoiceController@Status_Update')->name('Status_Update');

Route::get('Invoice_Paid','InvoiceController@Invoice_Paid');

Route::get('Invoice_UnPaid','InvoiceController@Invoice_UnPaid');

Route::get('Invoice_Partial','InvoiceController@Invoice_Partial');

Route::resource('Archive','InvoiceArchiveController');

Route::get('Print_invoice/{id}','InvoiceController@Print_invoice');

Route::get('export_invoices','InvoiceController@export');

Route::group(['middleware' => ['auth']], function() {

    Route::resource('roles','RoleController');

    Route::resource('users','UserController');

});

Route::get('invoices_report','InvoiceReports@index');

Route::Post('Search_invoices','InvoiceReports@Search_invoices');

Route::get('customers_report','CustomerReports@index');

Route::Post('Search_customers','CustomerReports@searchCustomers');


Route::get('/{page}', 'AdminController@index');
