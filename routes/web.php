<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SearchResultsController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\AutoFirmaController;

// PDF Routes
Route::get('/pdf/factura/{invoice}', [PDFController::class, 'factura'])->name('pdf.factura');
Route::get('/pdf/ticket/{booking}', [PDFController::class, 'ticket'])->name('pdf.ticket');
Route::get('/pdf/contract/{booking}', [PDFController::class, 'contract'])->name('pdf.contract');

// AutoFirma Routes
Route::get('/autofirma/sign/{invoice}', [AutoFirmaController::class, 'showSignPage'])->name('autofirma.sign')->middleware('auth');
Route::post('/autofirma/save/{invoice}', [AutoFirmaController::class, 'saveSignature'])->name('autofirma.save')->middleware('auth');

Route::get('/', WelcomeController::class)->name('home');

Route::get('/search', SearchResultsController::class)->name('search.results');

Route::get('/booking/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
Route::post('/booking/confirm', [BookingController::class, 'confirm'])->name('booking.confirm');
Route::get('/booking/success/{booking}', [BookingController::class, 'success'])->name('booking.success');
Route::get('/mis-reservas', \App\Http\Livewire\ManageBooking::class)->name('booking.manage');

Route::get('/calendar/{vehicle}', [CalendarController::class, 'show'])->name('calendar.vehicle');

// Dynamic pages
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
Route::get('/menu/{slug}', [PageController::class, 'menu'])->name('menu.show');

// Fallback GET logout
Route::get('/admin/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});
