<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(array('prefix'=>'admin','namespace'=>'Admin','middleware'=>'csrf'),function(){
    Route::any('/',array('uses'=>'ArticleController@showGet'));
    Route::get('article/get',array('uses'=>'ArticleController@showGet','as'=>'admin/article/get'));
    Route::get('article/add',array('uses'=>'ArticleController@showAdd','as'=>'admin/article/add'));
    Route::get('article/modify',array('uses'=>'ArticleController@showModify','as'=>'admin/article/modify'));
    Route::post('article/add/post',array('uses'=>'ArticleController@add','as'=>'admin/article/add/post'));
    Route::post('article/modify/post',array('uses'=>'ArticleController@modify','as'=>'admin/article/modify/post'));
    Route::post('article/delete/post',array('uses'=>'ArticleController@delete','as'=>'admin/article/delete/post'));

    Route::get('category/add',array('uses'=>'CategoryController@showAdd','as'=>'admin/category/add'));
    Route::post('category/add',array('uses'=>'CategoryController@add','as'=>'admin/category/add/post'));
    Route::get('category/get',array('uses'=>'CategoryController@showGet','as'=>'admin/category/get'));

    Route::get('tag/add',array('uses'=>'TagController@showAdd','as'=>'admin/tag/add'));
    Route::post('tag/add',array('uses'=>'TagController@add','as'=>'admin/tag/add/post'));
    Route::get('tag/get',array('uses'=>'TagController@showGet','as'=>'admin/tag/get'));
});
Route::group(array('prefix'=>'admin','namespace'=>'Admin'),function(){
    Route::post('test',array('uses'=>'CategoryController@delete','as'=>'admin/tag/delete/post'));
});

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
