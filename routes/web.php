<?php

Route::get('/', 'HealthController@index')->name('health');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
