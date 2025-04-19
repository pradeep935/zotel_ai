<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\HomeController;

Route::post('/date-wise', [HomeController::class, 'dateWise']);
Route::post('/add-booking/{id}', [HomeController::class, 'addBooking']);
Route::get('/edit_data/{rcd_id}', [HomeController::class, 'editBooking']);
Route::get('/delete_data/{rcd_id}', [HomeController::class, 'deleteBooking']);
