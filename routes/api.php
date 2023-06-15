<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductsController;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::post('products/store',[ProductsController::class,'store']);
Route::get('products',[ProductsController::class,'index']);
Route::get('products/search',[ProductsController::class,'search']);
Route::put('products/update',[ProductsController::class,'update']);
Route::delete('products/delete',[ProductsController::class,'destroy']);


Route::middleware(['auth:sanctum'])->group(function ()
{
    // Ruta para el cierre de sesión
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('registerAdmin', [AuthController::class, 'registerAdmin'])->middleware('role:0');
    Route::post('registerEmployee', [AuthController::class, 'registerEmployee'])->middleware('role:1');
    Route::delete('deleteAccount/{id}',[AuthController::class,'delete']);






    // // Ruta para el portafolio
    // Route::post('/portafolios',[PortafolioController::class,'store']);
    // Route::put('/portafolios/{portafolio}',[PortafolioController::class,'update']);
    // Route::delete('/portafolios/{portafolio}',[PortafolioController::class,'destroy']);

    // //Rutas para el blog
    // Route::post('/blogs',[BlogController::class,'store']);
    // Route::put('/blogs/{blog}',[BlogController::class,'update']);
    // Route::delete('/blogs/{blog}',[BlogController::class,'destroy']);
});
