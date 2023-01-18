<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/columns', 'TrelloController@getData');
Route::post('/columns', 'TrelloController@addColumn');
Route::delete('/columns/{id}', 'TrelloController@deleteColumn');
Route::get('/columns/{id}/cards', 'TrelloController@getCards');
Route::put('/card/{id}/reorder' , 'TrelloController@reorderCards');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
