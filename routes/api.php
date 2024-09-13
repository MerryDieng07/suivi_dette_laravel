<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DetteController;
use App\Http\Controllers\PaiementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Ici, vous pouvez enregistrer des routes API pour votre application.
| Toutes les routes sont assignées au groupe de middleware "api".
|
*/

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Routes d'authentification
    |--------------------------------------------------------------------------
    */
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Routes des articles
    |--------------------------------------------------------------------------
    */
    Route::get('articles', [ArticleController::class, 'index']);
    Route::get('articles/{id}', [ArticleController::class, 'show']);
    Route::get('articles/libelle/{libelle}', [ArticleController::class, 'filterByLibelle']);
    Route::post('articles/libelle', [ArticleController::class, 'getByLibelle']);
    Route::post('articles/stock', [ArticleController::class, 'updateStock']);
    Route::patch('articles/{id}', [ArticleController::class, 'update']);

    /*
    |--------------------------------------------------------------------------
    | Routes des clients
    |--------------------------------------------------------------------------
    */
    Route::post('clients/telephone', [ClientController::class, 'clientByTelephone']);
    Route::get('clients', [ClientController::class, 'index']);
    Route::get('clients/status', [ClientController::class, 'getClients']);
    Route::get('clients/telephone/{telephone}', [ClientController::class, 'clientByTelephone']);
    Route::get('clients/{id}', [ClientController::class, 'show']);
    // Route::post('clients', [ClientController::class, 'store']);
    Route::put('clients/{id}', [ClientController::class, 'update']);
    Route::delete('clients/{id}', [ClientController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Routes des utilisateurs
    |--------------------------------------------------------------------------
    */
    Route::get('users', [UserController::class, 'index']); // Lister tous les utilisateurs avec filtre role/active
    Route::get('users/filter-role-active', [UserController::class, 'filterByRoleAndActive']); // Lister utilisateurs par rôle et statut
    Route::get('users/filter-active', [UserController::class, 'filterByActive']); // Lister utilisateurs par statut actif/inactif
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::apiResource('users', UserController::class);

    /*
    |--------------------------------------------------------------------------
    | Routes des dettes
    |--------------------------------------------------------------------------
    */
    Route::post('dettes', [DetteController::class, 'store']);
    Route::get('dettes/{id}', [DetteController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | Routes des paiements
    |--------------------------------------------------------------------------
    */
    Route::post('paiements', [PaiementController::class, 'store']);
});
