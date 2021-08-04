<?php

use App\Http\Controllers\BookController;
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
// routing BookController and use middleware to log all request
Route::apiResource('books', BookController::class)->middleware('logRoute');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
