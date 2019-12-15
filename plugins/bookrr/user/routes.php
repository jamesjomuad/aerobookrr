<?php


Route::any('/backend/backend/auth/signout',function(){
    \Event::fire('backend.user.logout',BackendAuth::getUser());
    \BackendAuth::logout();
    \Session::flush();
    return \Redirect::to('/');
})->middleware('web');

Route::get('/backend/backend/auth/signin',function(){
    return \Redirect::to('/');
})->middleware('web');