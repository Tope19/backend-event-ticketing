<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Event\EventsController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Event\EventsCategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("register", [RegisterController::class, "register"]);
Route::post("verify", [RegisterController::class, "verify_code"]);
Route::post("resend_code", [RegisterController::class, "resend_verification_code"]);
Route::post("login", [LoginController::class, "login"]);
Route::post("forgot-password", [PasswordController::class, "forgot_password"]);
Route::post("reset-password", [PasswordController::class, "reset_password"]);


Route::prefix("events")->group(function () {
    // Categories
    Route::get("/category/list", [EventsCategoryController::class, "list"]);
    Route::post("/category/create", [EventsCategoryController::class, "store"])->middleware(['auth.response']);

    // Events
    Route::get("/list", [EventsController::class, "list"]);
    Route::post("/create", [EventsController::class, "store"]);
});
