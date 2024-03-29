<?php

use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\StorageController;
use Illuminate\Support\Facades\Auth;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        if(Auth::check()){
            return view('main');
        }

        return redirect("/login");
    });

    Route::get('users', function () { return view('users'); });
    Route::get('storages', function () { return view('storages'); });
    Route::get('storages/pdf/{id}', [StorageController::class, 'pdfStorage']);

    Route::get('dashboard', function () {
        if(Auth::check() && Auth::user()->role === 1){
            return auth()
                ->user()
                ->createToken('auth_token', ['admin'])
                ->plainTextToken;
        }
        return redirect("/");
    });

    Route::get('clear/token', function () {
        if(Auth::check() && Auth::user()->role === 1) {
            Auth::user()->tokens()->delete();
        }
        return 'Token Cleared';
    });

    Route::get('/out', 'App\Http\Controllers\Auth\LoginController@logout')->name('out');
});

Auth::routes();

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('view:clear');
    $run = Artisan::call('route:clear');
    $run = Artisan::call('config:clear');
    $run = Artisan::call('cache:clear');
    $run = Artisan::call('config:cache');
    $run = Artisan::call('optimize:clear');

    return 'DONE'; //Return anything
});

//Route::get('/dbtest', function (){
//    $posts = DB::table('storages')->get();
//    return $posts;
//});

//Route::post('register', [RegisterController::class, 'register'])
//    ->middleware('restrictothers');
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
