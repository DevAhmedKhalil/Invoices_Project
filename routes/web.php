<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoicesAttachmentController;

// 🚪 Redirect root "/" to login or home if authenticated
Route::get('/', function () {
    return Auth::check() ? redirect('/home') : redirect('/login');
});

// 🔐 Auth routes: login, register, etc.
Auth::routes();

// 📦 Get products by section (used in invoice form)
Route::get('/sections/{section_id}/products', [ProductsController::class, 'getProductsBySection'])->name('sections.products');

// 🔒 All routes below require authentication
Route::middleware('auth')->group(function () {

    // 🏠 Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    /**
     * ===============================
     * 📄 Invoice Routes
     * ===============================
     */
    Route::resource('invoice', InvoicesController::class);
    Route::delete('invoices/force-delete/{id}', [InvoicesController::class, 'forceDestroy'])->name('invoices.force-delete');
    Route::post('/invoice/status/{id}', [InvoicesController::class, 'updateStatus'])->name('invoice.updateStatus');

    // Filter invoices by status
    Route::get('invoice-paid', [InvoicesController::class, 'invoicePaid'])->name('invoice.paid');
    Route::get('invoice-unpaid', [InvoicesController::class, 'invoiceUnpaid'])->name('invoice.unpaid');
    Route::get('invoice-partial', [InvoicesController::class, 'invoicePartial'])->name('invoice.partial');

    // Archived invoices
    Route::get('invoice-archived', [InvoicesController::class, 'invoiceArchived'])->name('invoice.archived');
    Route::patch('/invoices/{id}/restore', [InvoicesController::class, 'restore'])->name('invoices.restore');

    // Export and print
    Route::get('/invoices/export', [InvoicesController::class, 'export'])->name('invoices.export');
    Route::get('/invoice/print/{id}', [InvoicesController::class, 'print'])->name('invoice.print');

    // Invoice edit (custom route)
    Route::get('/edit-invoice/{invoiceId}', [InvoicesController::class, 'edit'])->name('invoices.edit');

    /**
     * ===============================
     * 📁 Invoice Attachments & Files
     * ===============================
     */
    Route::get('/invoices-details/{id}', [InvoicesDetailsController::class, 'edit'])->name('invoices-details.edit');
    Route::get('/view-file/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'viewFile']);
    Route::get('/download/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'downloadFile']);
    Route::post('/delete-file', [InvoicesDetailsController::class, 'destroy'])->name('delete-file');
    Route::post('/invoices/{invoiceId}/attachments', [InvoicesAttachmentController::class, 'store'])->name('invoices.attachments.store');

    /**
     * ===============================
     * 🧩 Section Routes
     * ===============================
     */
    Route::resource('section', SectionsController::class);
    Route::get('/section/{id}', [InvoicesController::class, 'getProducts']); // Helper route

    /**
     * ===============================
     * 📦 Product Routes
     * ===============================
     */
    Route::resource('product', ProductsController::class);


    Route::middleware(['auth'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
    });

    /**
     * ===============================
     * ⚙️ Admin Panel Catch-all Pages
     * ===============================
     */
    Route::get('/{page}', [AdminController::class, 'index']);

});
