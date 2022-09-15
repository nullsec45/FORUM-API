<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{RegisterController, AuthController, ForumController, ForumCommentController, UserController};

Route::group([
    "middleware" => "api"
], function($router){
    Route::prefix("auth")->group(function(){
        Route::post("register", [RegisterController::class,"register"]);
        Route::post("login", [AuthController::class,"login"]);
        Route::post("logout", [AuthController::class,"logout"]);
        Route::post("refresh", [AuthController::class,"refresh"]);
        Route::post("me", [AuthController::class,"me"]);
    });

  
    Route::get("user/@{username}", [UserController::class,"show"]);
    Route::get("user/@{username}/activity",[UserController::class,"activity"]);

    Route::get("forums/category/{category}", [ForumController::class,"category"]);

    Route::apiResource("forums", ForumController::class);
    Route::apiResource("forums.comments", ForumCommentController::class);




});