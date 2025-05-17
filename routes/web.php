<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoicesAttachmentController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SectionsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// 🚪 Default route (when user visits "/")
// If user is logged in, redirect to /home
// If not, redirect to /login page
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home');
    } else {
        return redirect('/login');
    }
});

// 🔐 Authentication routes (login, register, logout, etc.)
Auth::routes();

Route::get('/sections/{section_id}/products', [ProductsController::class, 'getProductsBySection'])->name('sections.products');

// 🔒 All routes inside this group require authentication
Route::middleware('auth')->group(function () {

    // 🏠 Home/dashboard page after login
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // 📄 Invoice management (CRUD)
    Route::resource('invoice', InvoicesController::class);
    Route::delete('/invoice/force/{id}', [InvoicesController::class, 'forceDestroy'])->name('invoice.forceDestroy');


    // 🧩 Section/category management (CRUD)
    Route::resource('section', SectionsController::class);

    // 📦 Product management (CRUD)
    Route::resource('product', ProductsController::class);

    Route::get('/section/{id}', [InvoicesController::class, 'getProducts']);

    // Invoices URLS
    Route::get('/invoices-details/{id}', [InvoicesDetailsController::class, 'edit'])->name('invoices-details.edit');

    Route::get('/view-file/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'viewFile']);

    Route::get('/download/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'downloadFile']);

    Route::post('/delete-file', [InvoicesDetailsController::class, 'destroy'])->name('delete-file');

    Route::post('/invoices/{invoiceId}/attachments', [InvoicesAttachmentController::class, 'store'])->name('invoices.attachments.store');

    Route::get('/edit-invoice/{invoiceId}', [InvoicesController::class, 'edit'])->name('invoices.edit');

    // 🧑‍💼 Admin panel or dynamic pages
    Route::get('/{page}', [AdminController::class, 'index']);
});
