<?php
use Carbon\Carbon;

// Register
Route::post('register',['uses'=>'Bookrr\User\Controllers\Customer@onRegister']);

// Login Frontend
Route::post('login',['uses'=>'Bookrr\User\Controllers\Auth@onLogin'])->middleware('web');

// Overide Backend Login
Route::post('/backend/backend/auth/signin',['uses'=>'Bookrr\User\Controllers\Auth@signin_onSubmit'])->middleware('web');

// Logout
Route::any('backend/backend/auth/signout','Bookrr\User\Controllers\Auth@signout')->middleware('web');

// Check Email
Route::get('check-email',['uses'=>'Bookrr\User\Controllers\Customer@onCheckEmail'])->middleware('web');