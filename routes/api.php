<?php

use App\Http\Controllers\InteractionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordResetController;

// Public Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);


// Protected Routes
Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('get/interactions', [InteractionController::class, 'index']);
    Route::post('create/interaction', [InteractionController::class, 'store']);
    Route::post('delete/interaction/{id}', [InteractionController::class, 'destroy']);
    Route::post('update/interaction/{id}', [InteractionController::class, 'update']);

});