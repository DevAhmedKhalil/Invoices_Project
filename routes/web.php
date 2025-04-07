<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoicesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();
//Auth::routes(['register' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::resource('invoices', InvoicesController::class);


Route::get('/{page}', [AdminController::class, 'index']); // This must be the last route
