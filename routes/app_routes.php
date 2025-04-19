<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AppApiController;

Route::get('/app-version', [AppApiController::class, 'appVersion']);
Route::post('/login', [AppApiController::class, 'login']);
Route::post('/verify-otp', [AppApiController::class, 'verifyOTP']);
Route::get('/get-profiledetails', [AppApiController::class, 'profileDetails']);


Route::group(["prefix"=>"attendance"], function(){
	Route::get('/site-list', [AppApiController::class, 'getSiteList']);
	Route::post('/check-location', [AppApiController::class, 'getLocation']);
	Route::post('/get-check-ins', [AppApiController::class, 'getCheckIns']);
	Route::post('/check-out', [AppApiController::class, 'markCheckout']);
});


Route::post('/attendance', [AppApiController::class, 'coachAttendList']);
Route::post('/get-users-by-site', [AppApiController::class, 'getUsersBySite']);
Route::post('/save-attendance', [AppApiController::class, 'saveAttendance']);
Route::post('/get-attendance', [AppApiController::class, 'getAttendance']);
