<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KataPentingController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::view('/','welcome');
Route::post('/',[TaskController::class,'addTask']);
Route::get('datatask', [TaskController::class,'getTask']);

Route::get('datapenting', [KataPentingController::class,'getData']);
Route::view('penting','formpenting');
Route::post('penting',[KataPentingController::class, 'addData']);