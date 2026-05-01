<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\PromoContentController;
use App\Http\Controllers\Ceo\ExecutiveReportController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Manager\ReportController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::controller(PublicPageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/tentang', 'about')->name('about');
    Route::get('/rute', 'routes')->name('routes.index');
    Route::get('/promo', 'promos')->name('promos.index');
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/kontak', 'contact')->name('contact');
    Route::post('/kontak', 'sendContact')->name('contact.send');
    Route::get('/cari-tiket', 'search')->name('flights.search');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'customer'])->name('dashboard');
    Route::get('/flights', [BookingController::class, 'search'])->name('flights.search');
    Route::get('/bookings/create/{schedule}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings', [BookingController::class, 'history'])->name('bookings.history');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{booking}/payment', [BookingController::class, 'payment'])->name('bookings.payment');
    Route::post('/bookings/{booking}/payment', [BookingController::class, 'uploadPayment'])->name('bookings.payment.upload');
    Route::get('/bookings/{booking}/ticket', [BookingController::class, 'ticket'])->name('bookings.ticket');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::get('/master/{resource}', [MasterDataController::class, 'index'])->name('master.index');
    Route::get('/master/{resource}/create', [MasterDataController::class, 'create'])->name('master.create');
    Route::post('/master/{resource}', [MasterDataController::class, 'store'])->name('master.store');
    Route::get('/master/{resource}/{id}/edit', [MasterDataController::class, 'edit'])->name('master.edit');
    Route::put('/master/{resource}/{id}', [MasterDataController::class, 'update'])->name('master.update');
    Route::delete('/master/{resource}/{id}', [MasterDataController::class, 'destroy'])->name('master.destroy');
    Route::get('/aircrafts/{aircraft}/seats', [MasterDataController::class, 'seats'])->name('aircrafts.seats');
    Route::post('/aircrafts/{aircraft}/seats/generate', [MasterDataController::class, 'generateSeats'])->name('aircrafts.seats.generate');

    Route::get('/booking-offline', [BookingManagementController::class, 'offlineForm'])->name('booking-offline.create');
    Route::post('/booking-offline', [BookingManagementController::class, 'offlineStore'])->name('booking-offline.store');
    Route::get('/transactions', [BookingManagementController::class, 'transactions'])->name('transactions.index');
    Route::get('/transactions/{booking}', [BookingManagementController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{booking}/confirm-payment', [BookingManagementController::class, 'confirmPayment'])->name('transactions.confirm-payment');
    Route::post('/transactions/{booking}/cancel', [BookingManagementController::class, 'cancel'])->name('transactions.cancel');
    Route::get('/transactions/{booking}/ticket', [BookingController::class, 'ticket'])->name('transactions.ticket');

    Route::get('/promos', [PromoContentController::class, 'promos'])->name('promos.index');
    Route::post('/promos', [PromoContentController::class, 'storePromo'])->name('promos.store');
    Route::get('/faqs', [PromoContentController::class, 'faqs'])->name('faqs.index');
    Route::post('/faqs', [PromoContentController::class, 'storeFaq'])->name('faqs.store');
    Route::delete('/faqs/{faq}', [PromoContentController::class, 'deleteFaq'])->name('faqs.destroy');
    Route::get('/contacts', [PromoContentController::class, 'contacts'])->name('contacts.index');
});

Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'manager'])->name('dashboard');
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/sales/export', [ReportController::class, 'exportSales'])->name('reports.sales.export');
    Route::get('/reports/sales/print', [ReportController::class, 'printSales'])->name('reports.sales.print');
    Route::get('/reports/occupancy', [ReportController::class, 'occupancy'])->name('reports.occupancy');
    Route::get('/reports/routes', [ReportController::class, 'popularRoutes'])->name('reports.routes');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/promos', [ReportController::class, 'promos'])->name('promos.index');
    Route::post('/promos/{promo}/approve', [ReportController::class, 'approvePromo'])->name('promos.approve');
    Route::post('/promos/{promo}/reject', [ReportController::class, 'rejectPromo'])->name('promos.reject');
});

Route::middleware(['auth', 'role:ceo'])->prefix('ceo')->name('ceo.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'ceo'])->name('dashboard');
    Route::get('/reports/strategic', [ExecutiveReportController::class, 'strategic'])->name('reports.strategic');
    Route::get('/reports/performance', [ExecutiveReportController::class, 'performance'])->name('reports.performance');
    Route::get('/reports/export', [ExecutiveReportController::class, 'exportExecutive'])->name('reports.export');
    Route::get('/reports/print', [ExecutiveReportController::class, 'printExecutive'])->name('reports.print');
});
