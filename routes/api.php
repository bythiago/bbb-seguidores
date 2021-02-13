<?php

use App\Http\Controllers\BrotherController;
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

Route::get('/', [BrotherController::class, 'json']);
Route::get('/import', [BrotherController::class, 'import']);
Route::get('/graph', [BrotherController::class, 'graph']);
Route::get('/followes', [BrotherController::class, 'followes']);