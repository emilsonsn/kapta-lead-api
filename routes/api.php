<?php

use App\Http\Controllers\ChannelController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\SubscriptionPlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']);

Route::get('validateToken', [AuthController::class, 'validateToken']);
Route::post('recoverPassword', [UserController::class, 'passwordRecovery']);
Route::post('updatePassword', [UserController::class, 'updatePassword']);


Route::get('validateToken', [AuthController::class, 'validateToken']);

Route::middleware('jwt')->group(function(){

    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('user')->group(function(){
        Route::get('/me', [UserController::class, 'getUser']);
        Route::post('/store', [UserController::class, 'create']);
        Route::patch('{user}', [UserController::class, 'update']);
        Route::delete('{user}', [UserController::class, 'delete']);
    });

    Route::prefix('channel')->group(function () {
        Route::get('/', [ChannelController::class, 'list']);
        Route::post('/store', [ChannelController::class, 'store']);
        Route::patch('/{channel}', [ChannelController::class, 'update']);
        Route::delete('/{channel}', [ChannelController::class, 'delete']);
    });

    Route::prefix('subscription-plan')->group(function(){
        Route::get('/', [SubscriptionPlanController::class, 'list']);
    });

    Route::prefix('link')->group(function () {
        Route::get('/', [LinkController::class, 'list']);
        Route::post('/', [LinkController::class, 'store']);
        Route::put('{link}', [LinkController::class, 'update']);
        Route::delete('{link}', [LinkController::class, 'delete']);
    });
});
