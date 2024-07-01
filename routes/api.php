<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SurveyController;
use App\Http\Middleware\CheckSurveyAccess;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\RespondentGroupController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::prefix('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });
    Route::post('/submit-response', [ResponseController::class, 'store']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/survey', \App\Http\Controllers\SurveyController::class);
    // Route::get('/survey/slug/{slug}', [SurveyController::class, 'showBySlug']);
    Route::get('/survey/slug/{slug}', [SurveyController::class, 'showBySlug'])->middleware(CheckSurveyAccess::class);
});
// Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/public/survey/{slug}', [SurveyController::class, 'showBySlug']);
