<?php

use App\Http\Controllers\NoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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


Route::get('show',[NoteController::class,'show']);
Route::get('destroy/{id}',[NoteController::class,'destroy']);

Route::post('search',[NoteController::class,'search']);
Route::post('store',[NoteController::class,'store']);
Route::post('update',[NoteController::class,'update']);



  

