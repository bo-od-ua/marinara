<?php

use App\Http\Controllers\StorageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'auth:sanctum'], function() {

    Route::get('storages', [StorageController::class, 'storage']); // list all storages
    Route::get('storages/{id}', [StorageController::class, 'singleStorage']); // get a post
    Route::post('storages', [StorageController::class, 'createStorage']); // add a new post
    Route::put('storages/{id}', [StorageController::class, 'updateStorage']); // updating a post
    Route::delete('storages/{id}', [StorageController::class, 'deleteStorage']); // delete a post

    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']); // add a new user
    Route::delete('users/{id}', [UserController::class, 'destroy']); // delete a user

//    Route::post('users/writer', [StorageController::class, 'createWriter']); // add a new user with writer scope
//    Route::post('users/subscriber', [StorageController::class, 'createSubscriber']); // add a new user with subscriber scope
//    Route::delete('users/{id}', [StorageController::class, 'deleteUser']); // delete a user
});

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
