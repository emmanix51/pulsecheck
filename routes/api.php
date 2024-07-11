<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SurveyController;
use App\Http\Middleware\CheckSurveyAccess;
use App\Http\Controllers\ResponseController;
use App\Http\Middleware\CheckPublicSurveyAccess;
use App\Http\Controllers\SurveyResultsController;
use App\Http\Controllers\RespondentGroupController;
use App\Http\Controllers\TemplateController;

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
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/survey', \App\Http\Controllers\SurveyController::class);
    Route::resource('/template', TemplateController::class);
    // Route::get('/survey/slug/{slug}', [SurveyController::class, 'showBySlug'])
    //     ->middleware(CheckSurveyAccess::class);
    // Route::get('/survey/slug/{slug}', [SurveyController::class, 'showBySlug']);
    Route::get('/survey/slug/{slug}', [SurveyController::class, 'showBySlug'])
        ->middleware(CheckSurveyAccess::class);

    Route::get('/survey/responses/{id}', [SurveyResultsController::class, 'getResponse']);
    Route::get('/survey/{id}/results', [SurveyResultsController::class, 'show']);
    Route::get('/survey/{id}/results/descriptive', [SurveyResultsController::class, 'showDescriptiveData']);
    Route::get('/survey/{id}/details', [SurveyResultsController::class, 'getSurveyDetails']);
    Route::get('/survey/{id}/export', [SurveyResultsController::class, 'exportAllResponses']);
    Route::get('/survey/{id}/results/visualization', [SurveyResultsController::class, 'surveyResultsVisual']);
    Route::get('/survey/{id}/results/visualization/questions', [SurveyResultsController::class, 'getSurveyQuestions']);
    Route::get('/survey/question/{id}', [SurveyResultsController::class, 'getSurveyQuestion']);
});

Route::post('/submit-response', [ResponseController::class, 'store']);
// Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/public/survey/slug/{slug}', [SurveyController::class, 'showPublicBySlug'])->middleware(CheckPublicSurveyAccess::class);
