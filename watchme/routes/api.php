<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\watchmeController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/projects/times/add',[watchmeController::class,'api_add_time_to_project']);
Route::post('/projects/add',[watchmeController::class,'api_add_new_project']);
Route::get('/projects',[watchmeController::class,'api_get_projects']);
Route::post('/times/add',[watchmeController::class,'api_add_new_time']);
Route::get('/all',[watchmeController::class,'api_get_all']);
