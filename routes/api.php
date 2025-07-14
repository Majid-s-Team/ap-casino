<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\CasinoController;
use App\Http\Controllers\GamePlayedController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SlotSessionController;
use App\Http\Controllers\W2gsFormController;
use App\Http\Controllers\FreePlayController;
use App\Http\Controllers\TeamPlayController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CardBuildingController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ReportController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::get('/privacy-policy', [StaticController::class, 'privacyPolicy']);
Route::get('/terms-and-conditions', [StaticController::class, 'terms']);
Route::post('/upload-attachment', [Controller::class, 'uploadAttachment']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    Route::get('/my-profile', [ProfileController::class, 'profile']);
    Route::put('/edit-profile', [ProfileController::class, 'update']);

    Route::apiResource('casinos', CasinoController::class);
    Route::apiResource('games', GamePlayedController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('slot-sessions', SlotSessionController::class);
    Route::apiResource('w2gs-forms', W2gsFormController::class);
    Route::apiResource('free-plays', FreePlayController::class);
    Route::apiResource('team-plays', TeamPlayController::class);
    Route::apiResource('expenses', ExpenseController::class);
    Route::apiResource('card-buildings', CardBuildingController::class);

    Route::apiResource('expense-categories', ExpenseCategoryController::class);

    Route::get('/individual-logs', [LogController::class, 'individualLogs']);
    Route::post('/individual-logs', [LogController::class, 'storeIndividual']);

    Route::get('/team-logs', [LogController::class, 'teamLogs']);
    Route::post('/team-logs', [LogController::class, 'storeTeam']);


    Route::get('/hand-pays', [LogController::class, 'handPays']);
    Route::post('/hand-pays', [LogController::class, 'storeHandPay']);

    Route::put('individual-log/{id}', [LogController::class, 'updateIndividual']);
    Route::delete('individual-log/{id}', [LogController::class, 'deleteIndividual']);

    Route::put('team-log/{id}', [LogController::class, 'updateTeam']);
    Route::delete('team-log/{id}', [LogController::class, 'deleteTeam']);

 
    Route::put('handpay/{id}', [LogController::class, 'updateHandPay']);
    Route::delete('handpay/{id}', [LogController::class, 'deleteHandPay']);
    Route::get('/report/summary', [ReportController::class, 'summary']);
    Route::get('/report/my-reports', [ReportController::class, 'myReports']);
    Route::get('/report/tax', [ReportController::class, 'taxReport']);

    Route::get('/all-logs', [ReportController::class, 'allLogs']);


});


Route::middleware(['auth:sanctum', 'is.admin'])->prefix('admin')->group(function () {
    Route::get('/users', [UserManagementController::class, 'index']);
    Route::get('/users/{id}', [UserManagementController::class, 'show']);
    Route::post('/users', [UserManagementController::class, 'store']);
    Route::put('/users/{id}', [UserManagementController::class, 'update']);
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy']);
    Route::put('/users/{id}/role', [UserManagementController::class, 'updateRole']);
    Route::get('/users/search', [UserManagementController::class, 'search']);
    Route::get('/users-stats', [UserManagementController::class, 'stats']);
});