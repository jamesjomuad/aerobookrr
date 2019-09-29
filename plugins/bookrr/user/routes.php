<?php
use Carbon\Carbon;

// Register
Route::post('register',['uses'=>'Aeroparks\User\Controllers\Customer@onRegister']);

// Login Frontend
Route::post('login',['uses'=>'Aeroparks\User\Controllers\Auth@onLogin'])->middleware('web');

// Overide Backend Login
Route::post('/backend/backend/auth/signin',['uses'=>'Aeroparks\User\Controllers\Auth@signin_onSubmit'])->middleware('web');

// Logout
Route::any('backend/backend/auth/signout','Aeroparks\User\Controllers\Auth@signout')->middleware('web');

// Check Email
Route::get('check-email',['uses'=>'Aeroparks\User\Controllers\Customer@onCheckEmail'])->middleware('web');