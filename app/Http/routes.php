<?php

Route::group(['middleware' => 'web'], function () {
    /*
     * 画面
     */
    Route::auth();

    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/home', 'HomeController@index');

    /*
     * API関連
     */
    Route::group(['prefix' => 'api'], function () {
        // 記事
        Route::resource('article', 'ArticleController');

        // コメント
        Route::resource('comment', 'CommentController');

        // タグ関連
        Route::resource('tag', 'TagController');

        // ユーザー関連
        Route::resource('user', 'UserController');
    });
});
