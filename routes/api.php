<?php

use App\Http\Controllers\API\AssignmentController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\MaterialController;
use App\Http\Controllers\API\SubmissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Authenticated routes
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');

// Course routes
Route::middleware('auth:sanctum')->prefix('courses')->group(function () {
    Route::get('/', [CourseController::class, 'index'])->name('courses.index');
    Route::post('/', [CourseController::class, 'store'])->name('courses.store');
    Route::put('/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::post('/{id}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
});

// Material routes
Route::middleware('auth:sanctum')->prefix('materials')->group(function () {
    Route::post('/', [MaterialController::class, 'upload'])->name('materials.upload');
    Route::get('/{id}/download', [MaterialController::class, 'download'])->name('materials.download');
});

// Assignment routes
Route::post('assignments', [AssignmentController::class, 'store'])->middleware('auth:sanctum')->name('assignments.store');

// Submission routes
Route::middleware('auth:sanctum')->prefix('submissions')->group(function () {
    Route::post('/', [SubmissionController::class, 'store'])->name('submissions.store');
    Route::post('/{id}/grade', [SubmissionController::class, 'grade'])->name('submissions.grade');
});
