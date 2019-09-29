<?php

use Backend\Models\User as BackUser;
use Backend\Models\UserRole;
use Faker\Factory as Faker;
use Carbon\Carbon;


// API Product/Coupon
Route::group(['prefix' => 'api/v1/'],function(){
    Route::any('coupons/{code}',['uses'=>'Bookrr\Store\Controllers\Coupon@getCoupon']);
    Route::any('coupons/',['uses'=>'Bookrr\Store\Controllers\Coupon@getCoupons']);
    Route::any('products/{id}',['uses'=>'Bookrr\Store\Controllers\Product@getProduct']);
    Route::any('products/',['uses'=>'Bookrr\Store\Controllers\Product@getProducts']);
});