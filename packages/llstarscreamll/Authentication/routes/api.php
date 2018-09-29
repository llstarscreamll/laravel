<?php

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

Route::prefix('api')
    ->middleware('api')
    ->namespace('llstarscreamll\Authentication\Http\Api\Controllers')
    ->group(function ($route) {
        $route->post('test', 'Authentication@login');
    });
