<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SurveyController;
use App\Http\Middleware\CheckSurveyAccess;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\NotificationController;
use App\Http\Middleware\CheckPublicSurveyAccess;
use App\Http\Controllers\SurveyResultsController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\RespondentGroupController;
use App\Models\Program;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::prefix('admin')->group(function () {
        Route::get('/getsurveys', [SurveyController::class, 'getSurveys']);
        Route::get('/getresponses', [ResponseController::class, 'getAllResponses']);
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });
    Route::prefix('surveymaker')->group(function () {
        Route::get('/getownsurveys', [SurveyController::class, 'getOwnSurveys']);
        Route::get('/getresponsesownsurveys', [ResponseController::class, 'getAllResponsesToMySurveys']);
    });
    Route::get('/results', [SurveyResultsController::class, 'index']);
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/survey', \App\Http\Controllers\SurveyController::class);
    Route::resource('/template', TemplateController::class);
    // Route::get('/survey/slug/{slug}', [SurveyController::class, 'showBySlug'])
    //     ->middleware(CheckSurveyAccess::class);
    // Route::get('/survey/slug/{slug}', [SurveyController::class, 'showBySlug']);
    Route::get('/survey/slug/{slug}', [SurveyController::class, 'showBySlug'])
    ->middleware(CheckSurveyAccess::class);
    Route::post('/survey/{id}/distribute', [SurveyController::class, 'distribute']);
    Route::get('/survey/responses/{id}', [SurveyResultsController::class, 'getResponse']);
    Route::get('/survey/{id}/results', [SurveyResultsController::class, 'show']);
    Route::get('/survey/{id}/results/descriptive', [SurveyResultsController::class, 'showDescriptiveData']);
    Route::get('/survey/{id}/details', [SurveyResultsController::class, 'getSurveyDetails']);
    Route::get('/survey/{id}/export', [SurveyResultsController::class, 'exportAllResponses']);
    Route::get('/survey/{id}/results/visualization', [SurveyResultsController::class, 'surveyResultsVisual']);
    Route::get('/survey/{id}/results/visualization/questions', [SurveyResultsController::class, 'getSurveyQuestions']);
    Route::get('/survey/{id}/pca', [SurveyResultsController::class, 'runPCA']);
    
    Route::get('/survey/question/{id}', [SurveyResultsController::class, 'getSurveyQuestion']);
    Route::get('/response/{id}', [ResponseController::class, 'show']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'deleteNotification']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::get('/dashboard/{id}', [DashboardController::class, 'getDashboard']);
    Route::get('/my-surveys/{id}', [DashboardController::class, 'getRespondentSurveys']);
    Route::get('/my-responses/{id}', [DashboardController::class, 'getRespondentResponses']);
});

Route::resource('/college', CollegeController::class);
Route::get('/getprograms', [ProgramController::class, 'getPrograms']);
Route::resource('/program', ProgramController::class);
Route::post('/submit-response', [ResponseController::class, 'store']);
// Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::get('/public/survey/slug/{slug}', [SurveyController::class, 'showPublicBySlug'])->middleware(CheckPublicSurveyAccess::class);
Route::get('/public-surveys', [DashboardController::class, 'getPublicSurveys']);
Route::get('/test', function () {
    return 'Scream the N wordddd';
});