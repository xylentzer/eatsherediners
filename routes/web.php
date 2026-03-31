<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

require __DIR__.'/auth.php';

//Cx routing interface

Route::get('/', fn() => view('home'))->name('home');
Route::get('/home', fn() => view('home'))->name('home');
Route::get('/service', fn() => view('service'))->name('service');
Route::get('/menu', fn() => view('menu'))->name('menu');
Route::get('/contact', fn() => view('contact'))->name('contact');
Route::get('/about', fn() => view('about'))->name('about');

// Allow both GET & POST for /menu
Route::match(['get', 'post'], '/menu', fn() => view('menu'));

//admin routing interface

Route::get('/admin/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
Route::get('/admin/order', fn() => view('admin.order'))->name('admin.order');
Route::get('/admin/revenue', fn() => view('admin.revenue'))->name('admin.revenue');
Route::get('/admin/inventory', fn() => view('admin.inventory'))->name('admin.inventory');
Route::get('/admin/item', fn() => view('admin.item'))->name('admin.item');
Route::get('/admin/customer', fn() => view('admin.customer'))->name('admin.customer');

// Admin Authentication Routes
Route::get('/admin/login', fn() => view('admin.login'))->name('admin.login');
Route::post('/admin/logout', fn() => redirect()->route('admin.login'))->name('admin.logout');



Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
    Route::get('/admin/order', fn() => view('admin.order'))->name('admin.order');
    Route::get('/admin/revenue', fn() => view('admin.revenue'))->name('admin.revenue');
    Route::get('/admin/inventory', fn() => view('admin.inventory'))->name('admin.inventory');
    Route::get('/admin/item', fn() => view('admin.item'))->name('admin.item');
    Route::get('/admin/customer', fn() => view('admin.customer'))->name('admin.customer');
});


Route::get('/dashboard', function () {
    return view('admin.dashboard'); // your admin dashboard view
})->middleware(['auth', 'verified'])->name('dashboard');



//login/register
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

// ✅ PROTECTED ROUTE (Dashboard)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});