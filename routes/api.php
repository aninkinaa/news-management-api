<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CommentController;

Route::post('/login', [AuthController::class, 'login']);
Route::get('news', [NewsController::class, 'index']);
Route::get('news/{id}', [NewsController::class, 'show']);

Route::middleware('auth:api')->group(function () {

    Route::middleware('is_admin')->group(function () {
        Route::post('news', [NewsController::class, 'store']);
        Route::match(['put', 'patch'], 'news/{id}', [NewsController::class, 'update']);
        Route::delete('news/{id}', [NewsController::class, 'destroy']);
    });

    Route::middleware('is_not_admin')->group(function(){
        Route::post('news/{news}/comments', [CommentController::class, 'store']);
        Route::match(['put', 'patch'], 'comments/{comment}', [CommentController::class, 'update']);
        Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
    });
});