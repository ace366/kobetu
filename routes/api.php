<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolController;
use App\Models\Classroom;

Route::get('/schools', [SchoolController::class, 'search']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/classrooms', function(Request $request) {
    $q = $request->get('q');
    return Classroom::where('name', 'like', "%{$q}%")
                    ->orWhere('code', 'like', "%{$q}%")
                    ->limit(20)
                    ->get(['id','code','name']);
});