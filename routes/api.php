<?php

//会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');

//ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');

//ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

//写真投稿
Route::post('/photos', 'PhotoController@create')->name('photo.create');

//写真一覧
Route::get('/photos', 'PhotoController@index')->name('photo.index');

//写真詳細
Route::get('/photos/{id}', 'PhotoController@show')->name('photo.show');

//コメント
Route::post('/photos/{photo}/comments', 'PhotoController@addComment')->name('photo.comment');

//いいね
Route::put('/photos/{id}/like', 'PhotoController@like')->name('photo.like');

//いいね解除
Route::delete('/photos/{id}/like', 'PhotoController@unlike');

//トークンリフレッシュ
Route::get('/refresh-token', function (Illuminate\Http\Request $request) {
    $request->session()->regenerateToken();

    return response()->json();
});

//ログインユーザー
Route::get('/user', function () {
    return Auth::user();
})->name('user');
