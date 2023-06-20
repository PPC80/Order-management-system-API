<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CartDetailController;
use App\Http\Controllers\Api\ImageController;
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

//Login/Register
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

//Products
Route::get('products',[ProductsController::class,'index']);
Route::post('products/store',[ProductsController::class,'store']);
Route::get('products/search',[ProductsController::class,'search']);
Route::put('products/update',[ProductsController::class,'update']);
Route::delete('products/delete',[ProductsController::class,'destroy']);

//Carts
Route::post('cart/create',[CartController::class,'create']);
Route::delete('cart/delete',[CartController::class,'destroy']);

//Cart Details
Route::get('cart',[CartDetailController::class,'index']);
Route::post('cart/add',[CartDetailController::class,'add']);
Route::get('cart/search',[CartDetailController::class,'search']);
Route::put('cart/update',[CartDetailController::class,'update']);
Route::delete('cart/delete',[CartDetailController::class,'destroy']);


Route::post('upload', [ImageController::class, 'store']);


Route::middleware(['auth:sanctum'])->group(function ()
{
    //Logout
    Route::post('logout', [AuthController::class, 'logout']);

    //Register Admin/Employee - Delete Account
    Route::post('registerAdmin', [AuthController::class, 'registerAdmin'])->middleware('role:0');
    Route::post('registerEmployee', [AuthController::class, 'registerEmployee'])->middleware('role:1');
    Route::delete('deleteAccount/{id}',[AuthController::class,'delete']);
});
