<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarberController;

Route::post('/auth/login/', [AuthController::class, 'login']);
Route::post('/auth/logout/', [AuthController::class, 'logout']);
Route::post('/auth/refresh/', [AuthController::class, 'refresh']);
Route::post('/user', [AuthController::class, 'create']);

Route::get('/user', [UserController::class, 'read']);
Route::get('/user/favorites', [UserController::class, 'getfavorites']);
Route::post('/user/favorite', [UsererController::class, 'addfavorite']);
Route::get('/user/appointments', [UserController::class, 'getappointments']);

Route::get('/barbers', [BarberController::class, 'list']);
Route::get('/barber', [BarberController::class, 'one']);
Route::post('/barber/{id}/appointment', [BarberController::class, 'setappointment']);

Route::get('/search', [BarberController::class, 'search']);