<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to dashboard
Route::get('/', fn () => redirect()->route('dashboard'));

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Product routes
    Route::resource('products', ProductController::class);

    // Customer routes
    Route::resource('customers', CustomerController::class);

    // Sale routes
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');

    // CRM routes (admin only)
    Route::middleware('admin')->prefix('crm')->name('crm.')->group(function () {
        Route::get('/lost-customers', [CrmController::class, 'lostCustomers'])->name('lostCustomers');
        Route::get('/assign/{customer}', [CrmController::class, 'assign'])->name('assign');
        Route::post('/assign/{customer}', [CrmController::class, 'doAssign'])->name('doAssign');
        Route::post('/send-email/{customer}', [CrmController::class, 'sendEmail'])->name('sendEmail');
        Route::post('/send-sms/{customer}', [CrmController::class, 'sendSms'])->name('sendSms');
    });

    // Employee routes (admin only)
    Route::middleware('admin')->prefix('employees')->name('employees.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/{employee}/assignments', [EmployeeController::class, 'assignments'])->name('assignments');
    });

    // Settings (admin only)
    Route::middleware('admin')->prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'edit'])->name('edit');
        Route::put('/', [SettingController::class, 'update'])->name('update');
    });
});
