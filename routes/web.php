<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SearchResultsController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\PageController;

Route::get('/', WelcomeController::class)->name('home');

Route::get('/search', SearchResultsController::class)->name('search.results');
Route::get('/book/{vehicle}/{customer}', [BookingController::class, 'form'])->name('bookings.form');
Route::get('/calendar/{vehicle}', [CalendarController::class, 'show'])->name('calendar.vehicle');

// Dynamic pages
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
Route::get('/menu/{slug}', [PageController::class, 'menu'])->name('menu.show');

