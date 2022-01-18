<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();

Route::middleware(['auth'])->group(function () {
	Route::get('/', [App\Http\Controllers\ContactController::class, 'index']);
	
	Route::resources([
	    'contact' => App\Http\Controllers\ContactController::class
	]);

	Route::post('contact/file-import', [App\Http\Controllers\ContactController::class, 'fileImport'])->name('contact.import');

	Route::get('track-click', [App\Http\Controllers\ContactController::class, 'trackToKlaviyo'])->name('track');
	
});

