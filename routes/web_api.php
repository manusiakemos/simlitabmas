<?php
/*
|--------------------------------------------------------------------------
| WEB API Routes
|--------------------------------------------------------------------------
|
|route for api for front end
|
*/
Route::get('setting/{type}', 'WebApiController@setting')->name('web.setting');
Route::get('setting-name/{type}', 'WebApiController@settingName')->name('web.setting.name');
Route::get('menu', 'WebApiController@menu')->name('web.menu');
Route::get('slider', 'WebApiController@slider')->name('web.slider');

Route::get('berita/terbaru', 'WebApiController@terbaru')->name('web.berita.terbaru');
Route::get('berita/terpopuler', 'WebApiController@terpopuler')->name('web.berita.terpopuler');
