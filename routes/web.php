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

//写真ダウンロード
//下のやつ(index)より上に書かなければいけない→上からマッチするから、下のにかかると
//よくない。
Route::get('/photos/{photo}/download', 'PhotoController@download');

Route::get('/{any?}', function () {
    return view('index');
})->where('any', '.+');
