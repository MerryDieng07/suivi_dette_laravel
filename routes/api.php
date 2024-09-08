<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SomeController;


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

Route::middleware('auth:passport')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware('auth:api')->group(function () {
//     Route::post('v1/logout', [AuthController::class, 'logout']);
//     Route::apiResource('v1/articles', ArticleController::class);
     Route::apiResource('v1/users', UserController::class);
     Route::apiResource('v1/clients', ClientController::class);
// });

// Routes non protégées par l'auth middleware
Route::post('v1/login', [AuthController::class, 'login']);
Route::post('v1/register', [AuthController::class, 'register']);

// Route spécifique pour filtrer les articles par libelle
Route::get('v1/articles/libelle/{libelle}', [ArticleController::class, 'filterByLibelle']);

// Route spécifique pour filtrer les clients par téléphone
Route::post('v1/clients/telephone', [ClientController::class, 'clientByTelephone']);
// Route::get('v1/clients/telephone/{telephone}', [ClientController::class, 'findByTelephone']);
Route::get('v1/clients/generate-qrcode', [SomeController::class, 'generateQrCode']);
Route::get('v1/clients/manipulate-image', [SomeController::class, 'manipulateImage']);


