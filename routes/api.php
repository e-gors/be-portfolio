<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\ExperienceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;

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

Route::post('login', [Controller::class, 'login']);
Route::post('register', [Controller::class, 'register']);

// public or homepage display
Route::post('feedbacks', [FeedbackController::class, 'store']);
Route::get('feedbacks', [FeedbackController::class, 'index']);
Route::get('services', [ServiceController::class, 'index']);
Route::get('projects', [ProjectController::class, 'index']);
Route::get('experiences', [ExperienceController::class, 'index']);

Route::get('roles', [RoleController::class, 'index']);

// download latest resume
Route::get('resume/download', [ResumeController::class, 'downloadLatest']);

// send emails to my email
Route::post('contacts', [Controller::class, 'sendContactMail']);

// private or dashboard display
Route::middleware('auth:api')->group(function () {
    Route::apiResource('users', UserController::class)->except('store');
    Route::apiResource('resume', ResumeController::class)->except('downloadLatest');
    Route::apiResource('feedbacks', FeedbackController::class)->except('store', 'index');
    Route::apiResource('roles', FeedbackController::class)->except('index');
    Route::apiResource('services', ServiceController::class)->except('index');
    Route::apiResource('projects', ProjectController::class)->except('index');
    Route::apiResource('experiences', ExperienceController::class)->except('index');
});
