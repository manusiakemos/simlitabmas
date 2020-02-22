<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
|route for api single page apps admin
|
*/
Route::post('login', 'AuthController@login');

Route::middleware(['auth:api'])->group(function(){
    //core
    Route::any('select-options/{type}', 'SelectOptionsController@index');

    Route::get('profile', 'ProfileController@edit')->name('profile.edit');
    Route::put('profile', 'ProfileController@update')->name('profile.update');
    Route::post('profile', 'ProfileController@avatar')->name('profile.avatar');

    Route::resource('user', 'UserController');
    
    Route::resource('chat', 'ChatController')->only(['store', 'show', 'destroy', 'edit']);

    //application routes
    Route::post('dashboard/broadcast','DashboardController@broadcast');
    Route::post('dashboard/chart/{type?}','DashboardController@chart');
    Route::post('dashboard/widget/{type?}','DashboardController@widget');

    Route::get('file-download/{id}', 'FileController@download')->name('file.download');
    Route::resource('file', 'FileController');

    Route::post('penelitian/{id}/upload','PenelitianController@upload')->name('penelitian.upload');
    Route::get('penelitian/detail/{ss_id}', 'PenelitianController@showDetail');
    Route::resource('penelitian', 'PenelitianController');

    Route::get('pengabdian/detail/{ss_id}', 'PengabdianController@showDetail');
    Route::resource('pengabdian', 'PengabdianController');

    Route::resource('anggota', 'AnggotaController');

    Route::resource('penelitiananggota', 'PenelitianAnggotaController');

    Route::get('pemberitahuan/unread', 'PemberitahuanController@unread');
    Route::post('pemberitahuan/readall', 'PemberitahuanController@readAll');
    Route::post('pemberitahuan/readme', 'PemberitahuanController@readme');
    Route::post('pemberitahuan/send', 'PemberitahuanController@send');
});