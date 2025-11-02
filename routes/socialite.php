<?php 

use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


Route::get('/auth/redirect', [SocialiteController::class,'redirectToGithub'])->name('github.login');

 
Route::get('/auth/callback',[SocialiteController::class,'handleGithubCallback']);