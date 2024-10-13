<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function() {
    return response()->json([
        'status' => false,
        'message' => 'Akses tidak diperbolehkan',
        'data' => null
    ], 401);
})->name('login');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Route::apiResource('products', ProductController::class);
// Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class)->middleware('auth:sanctum');
Route::get('products/search', [ProductController::class, 'search']);
Route::apiResource('categories', CategoryController::class)->middleware('auth:sanctum');
