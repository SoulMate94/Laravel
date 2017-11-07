<?php

Route::group([
    'middleware' => ['api','cors'],
    'prefix'     => 'api'], function() {
        Route::post('register', 'ApiController@register');
        Route::post('login', 'ApiController@login');
        Route::group(['middleware' => 'jwt.auth'], function() {
            Route::post('get_user_details', 'APIController@get_user_details');  //获取用户详情
        });
        Route::post('/api/login','StaffAuthController@login');
        Route::post('/api/register','StaffAuthController@register');
});