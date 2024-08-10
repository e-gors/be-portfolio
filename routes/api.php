<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResumeController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [UserController::class, 'login']);
Route::apiResource('roles', RoleController::class);
Route::apiResource('resume', ResumeController::class);

// download latest resume
Route::get('resume/latest/download', [ResumeController::class, 'downloadLatest']);

Route::middleware('auth:api')->group(function () {
    Route::apiResource('users', UserController::class);
});
