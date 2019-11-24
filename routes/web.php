<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', 'WebController@index')->name('index');

//Route::get('berita', 'WebController@berita')->name('berita');
//Route::get('berita/detail/{link}', 'WebController@beritaDetail')->name('berita.detail');

//Route::get('halaman/{link}', 'WebController@halaman')->name('halaman.detail');

//admin spa blade
Route::get('/', 'WebController@admin')->name('admin');
