<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResponseController;

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

Route::get('/',[ReportController::class, 'index'])->name('home');
Route::get('/login', function(){
    return view('login');
})->name('login');

Route::post('/store', [ReportController::class, 'store'])->name('store');
Route::post('/auth', [ReportController::class, 'auth'])->name('auth');

//route yang hanya bisa diakses setelah login dan rolenya
Route::middleware('IsLogin', 'CekRole:petugas')->group(function() {
    Route::get('/data/petugas',[ReportController::class, 'dataPetugas'])->name('data.petugas');
    //menampilkan form tambah atau ubah response
    Route::get('/response/edit/{report_id}',[ResponseController::class, 'edit'])->name('response.edit');
    // kirim data-response,menggunakan patch karena dia bisa berupa tambah data atau update data 8
    Route::patch('/response/update/{report_id}',[ResponseController::class, 'update'])->name('response.update');
});

//route untuk admin dan petugas setelah login
Route::middleware('IsLogin', 'CekRole:petugas,admin')->group(function() {
    Route::get('/logout', [ReportController::class, 'logout'])->name('logout');
});

Route::middleware(['IsLogin', 'CekRole:admin'])->group(function() {
    Route::get('/data', [ReportController::class, 'data'])->name('data');
    Route::delete('/delete/{id}', [ReportController::class, 'destroy'])->name('delete');
    Route::get('/export/pdf', [ReportController::class, 'exportPDF'])->name('export.pdf');
    Route::get('/created/pdf/{id}', [ReportController::class, 'createdPDF'])->name('created.pdf');
    Route::get('/export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');
});