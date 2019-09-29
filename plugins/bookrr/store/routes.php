<?php

use Backend\Models\User as BackUser;
use Backend\Models\UserRole;
use Faker\Factory as Faker;
use Carbon\Carbon;


// API Product/Coupon
Route::group(['prefix' => 'api/v1/'],function(){
    Route::any('coupons/{code}',['uses'=>'Aeroparks\Store\Controllers\Coupon@getCoupon']);
    Route::any('coupons/',['uses'=>'Aeroparks\Store\Controllers\Coupon@getCoupons']);
    Route::any('products/{id}',['uses'=>'Aeroparks\Store\Controllers\Product@getProduct']);
    Route::any('products/',['uses'=>'Aeroparks\Store\Controllers\Product@getProducts']);
});