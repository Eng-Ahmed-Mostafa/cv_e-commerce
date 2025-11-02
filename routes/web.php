<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


Auth::routes();


require __DIR__.'/socialite.php';
require __DIR__.'/users.php';
require __DIR__.'/admins.php';