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

$app->get('/', function () use ($app) {
    return $app->version();
});

//Notes Routes

$app->get('/notes', 'NotesController@index');
$app->get('/notes/{id:[\d]+}', [
    'as' => 'notes.show',
    'uses' =>  'NotesController@show'
]);
$app->post('/notes', 'NotesController@store');
$app->put('/notes/{id:[\d]+}', 'NotesController@update');
$app->delete('/notes/{id:[\d]+}', 'NotesController@destroy');
