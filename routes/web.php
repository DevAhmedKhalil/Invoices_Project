<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoicesController;
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

// 🔒 All routes inside this group require authentication
Route::middleware('auth')->group(function () {

    // 🏠 Home/dashboard page after login
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // 📄 Invoice management (CRUD)
    Route::resource('invoices', InvoicesController::class);

    // 🧩 Section/category management (CRUD)
    Route::resource('sections', SectionsController::class);

    // 📦 Product management (CRUD)
    Route::resource('products', ProductsController::class);

    // 🧑‍💼 Admin panel or dynamic pages
    Route::get('/{page}', [AdminController::class, 'index']);
});
