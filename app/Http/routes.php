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

$app->get('/', 'UserController@listAction');
$app->get('users', ['as' => 'users', 'uses' => 'UserController@listAction']);
$app->get('user/new', 'UserController@newAction');
$app->post('user/create', 'UserController@createAction');
$app->get('user/edit/{id}', 'UserController@editAction');
$app->post('user/update/{id}', 'UserController@updateAction');
