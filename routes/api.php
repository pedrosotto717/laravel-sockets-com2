<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/user', [UserController::class, 'getUser']);
    Route::post('/user', [UserController::class, 'updateProfile']);
    Route::post('/contacts', [ContactController::class, 'create']);
    Route::get('/contacts', [ContactController::class, 'getAllContacts']);
    Route::post('/messages/send', [MessageController::class, 'sendMessage']);
    Route::post('/messages/attachments', [MessageController::class, 'sendAttachment']);
    Route::get('/messages/{chatGroupId}', [MessageController::class, 'getMessages']);
    Route::post('/groups', [GroupController::class, 'createGroup']);
    Route::get('/groups', [GroupController::class, 'getUserGroups']);
    Route::put('/groups/add-new-member', [GroupController::class, 'addMemberToGroup']);
});

