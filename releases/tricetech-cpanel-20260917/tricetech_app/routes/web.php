<?php

use App\Http\Controllers\InquiryController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::view('/infrastructure', 'pages.infrastructure')->name('infrastructure');
Route::view('/cloud-software', 'pages.cloud-software')->name('cloud-software');
Route::view('/privacy', 'pages.privacy')->name('privacy');

Route::post('/inquiries', InquiryController::class)
    ->middleware('throttle:5,1')
    ->name('inquiries.store');

Route::redirect('/index.html', '/', 301);
Route::redirect('/infrastructure.html', '/infrastructure', 301);
Route::redirect('/cloud-software.html', '/cloud-software', 301);

Route::get('/sitemap.xml', function () {
    return response()->view('seo.sitemap')->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/robots.txt', function () {
    return response("User-agent: *\nAllow: /\nSitemap: ".route('sitemap')."\n")
        ->header('Content-Type', 'text/plain');
})->name('robots');
