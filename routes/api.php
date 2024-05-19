<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkerController;
use OpenApi\Generator;


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


Route::get('/api/documentation', function () {
    $openapi = Generator::scan([app_path('Http/Controllers')]);
    /* return response()->json($openapi); */
});

Route::post('/worker/clock-in', [WorkerController::class, 'clockIn']);
Route::get('/worker/clock-ins', [WorkerController::class, 'getClockIns']);
