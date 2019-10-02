<?php

#Callback when Success
Route::any('payment/success', 'Jomuad\Pxpay\Controllers\Transaction@success');

#Callback when fail
Route::any('payment/fail', 'Jomuad\Pxpay\Controllers\Transaction@fail');