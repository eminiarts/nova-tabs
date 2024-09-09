<?php

use Eminiarts\Tabs\Http\Controllers\ValidatorController;
use Illuminate\Support\Facades\Route;

Route::post('validate', [ValidatorController::class, 'index']);
