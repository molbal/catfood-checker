<?php

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

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('mail');
});

// Trigger manually
Route::get("/run", function () {
    Artisan::call("gather");
});
Route::get("/migrate", function () {
    echo Artisan::call("migrate");
    echo "e";
});
Route::get("/refresh", function () {
    echo Artisan::call("migrate:refresh");
    echo "r";
});

// Where is the absolute path again..
Route::get("/file", function() {
    echo __FILE__;
});
