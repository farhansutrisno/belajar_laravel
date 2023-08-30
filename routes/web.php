<?php

use App\Http\Controllers\SendEmailController;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Http\Controllers\CategoryController;
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
    return view('welcome');
});


Route::controller(CategoryController::class)->group(function () {
    Route::get('/category', 'index')->name('category.index');
    Route::get('/category/create', 'create')->name('category.create');
    Route::post('/category/create/store', 'store')->name('category.store');
    Route::get('/category/show/{id}', 'show')->name('category.show');
    Route::get('/category/edit/{id}', 'edit')->name('category.edit');
    Route::post('/category/update/{id}', 'update')->name('category.update');
    Route::delete('/category/destroy/{id}', 'destroy')->name('category.destroy');
    Route::get('/category/cetak', 'cetak')->name('category.cetak');
    Route::get('/category/export_excel', 'export_excel')->name('category.export_excel');
 });

/*
 Route::get('/send-email',function(){
    $data = [
        'name' => 'Farhan Sutrisno',
        'body' => 'Lagi Belajar Kirim Email'
    ];

    Mail::to('farhansutrisno98@gmail.com')->send(new SendEmail($data));

    dd("Email Berhasil dikirim.");
}); */

Route::get('/send-email', [SendEmailController::class, 'index'])->name('kirim-email');

Route::post('/post-email', [SendEmailController::class, 'store'])->name('post-email');
