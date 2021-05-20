<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageRekognitionController;
use App\Http\Controllers\PdfSortController;
use App\Http\Controllers\RekognitionResourceController;
use App\Http\Controllers\MenuController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' => ['auth']], function () {
    Route::post('/getProfile', [MenuController::class, 'getProfile']);

    Route::post('/imageRekognition/getList', [ImageRekognitionController::class, 'getList']);
    Route::post('/imageRekognition/fileupload', [ImageRekognitionController::class, 'upload']);
    Route::post('/imageRekognition/postDetectedResult', [ImageRekognitionController::class, 'postDetectedResult']);

    Route::post('/pdfsort/fileupload', [PdfSortController::class, 'upload']);
    Route::post('/pdfsort/pdfSortFinished', [PdfSortController::class, 'pdfSortFinished']);
    Route::post('/pdfSort/getTopPageDetect', [PdfSortController::class, 'getDetect']);
    Route::post('/pdfsort/sort', [PdfSortController::class, 'sort']);
    Route::post('/pdfsort/getSortResult', [PdfSortController::class, 'getSortResult']);

    Route::resource('/rekognition_resource', RekognitionResourceController::class);
});
