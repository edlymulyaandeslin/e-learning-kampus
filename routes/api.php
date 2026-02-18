<?php

use App\Http\Controllers\API\AssignmentController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\DiscussionController;
use App\Http\Controllers\API\MaterialController;
use App\Http\Controllers\API\ReportController;
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

    Route::middleware('isLecturer')->group(function () {
        Route::post('/', [CourseController::class, 'store'])->name('courses.store');
        Route::put('/{id}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');
    });

    Route::post('/{id}/enroll', [CourseController::class, 'enroll'])->middleware('isStudent')->name('courses.enroll');
});

// Material routes
Route::middleware('auth:sanctum')->prefix('materials')->group(function () {
    Route::post('/', [MaterialController::class, 'upload'])->middleware('isLecturer')->name('materials.upload');
    Route::get('/{id}/download', [MaterialController::class, 'download'])->middleware('isStudent')->name('materials.download');
});

// Assignment routes
Route::post('assignments', [AssignmentController::class, 'store'])->middleware('auth:sanctum')->middleware('isLecturer')->name('assignments.store');

// Submission routes
Route::middleware('auth:sanctum')->prefix('submissions')->group(function () {
    Route::post('/', [SubmissionController::class, 'store'])->middleware('isStudent')->name('submissions.store');
    Route::post('/{id}/grade', [SubmissionController::class, 'grade'])->middleware('isLecturer')->name('submissions.grade');
});

// Discussion routes
Route::middleware('auth:sanctum')->prefix('discussions')->group(function () {
    Route::post('/', [DiscussionController::class, 'store'])->name('discussions.store');
    Route::post('/{id}/replies', [DiscussionController::class, 'replies'])->name('discussions.replies');
});

// Report routes
Route::middleware('auth:sanctum')->prefix('reports')->group(function () {
    Route::get('/courses', [ReportController::class, 'courseReport'])->name('reports.courses');
    Route::get('/assignments', [ReportController::class, 'assignmentReport'])->name('reports.assignments');
    Route::get('/students/{id}', [ReportController::class, 'studentReport'])->name('reports.student');
});
