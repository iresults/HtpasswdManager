<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', 'UserController@listAll');
$app->get('users', ['as' => 'users', 'uses' => 'UserController@listAll']);
$app->get('user/new', 'UserController@new');
$app->post('user/create', 'UserController@create');
$app->get('user/edit/{id}', 'UserController@edit');
$app->post('user/update/{id}', 'UserController@update');
