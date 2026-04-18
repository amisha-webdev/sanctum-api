<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\ProfileController;   



Route::post('/signUp', [AuthController::class, 'signUp']);
Route::post('/login', [AuthController::class, 'login']);

Route::prefix('user')->middleware('auth:sanctum')->group(function () {  
     Route::post('/logout', [AuthController::class, 'logout']); 
     Route::get('/profile', [ProfileController::class, 'index']);
     Route::put('/profile/update', [ProfileController::class, 'update']); 
     Route::resource('posts',PostController::class); 
});





