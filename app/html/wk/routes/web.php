<?php

use App\Http\Controllers\ImageRekognitionController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

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

Route::get('/{any}', function() {return view('app');})->where('any', '.*');

Route::post('getProfile', [MenuController::class, 'getProfile']);
Route::post('imageRekognition/fileupload', [ImageRekognitionController::class, 'upload']);
Route::post('imageRekognition/postDetectedResult', [ImageRekognitionController::class, 'postDetectedResult']);
Route::post('imageRekognition/getList', [ImageRekognitionController::class, 'getList']);

