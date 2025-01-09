<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\CourseAnnouncementController;
use App\Http\Controllers\API\CourseCategoryController;
use App\Http\Controllers\API\CourseContentController;
use App\Http\Controllers\API\CourseController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:api')->group(function () {
        // Courses
        Route::get('my-courses', [CourseController::class, 'myCourses']);
        Route::get('courses', [CourseController::class, 'index']);
        Route::post('courses', [CourseController::class, 'store']);
        Route::get('courses/{id}', [CourseController::class, 'show']);
        Route::put('courses/{id}', [CourseController::class, 'update']);
        Route::delete('courses/{id}', [CourseController::class, 'destroy']);
        Route::get('courses/{id}/contents', [CourseController::class, 'listContent']);
        Route::get('courses/{id}/contents/{contentId}', [CourseController::class, 'detailContent']);
        Route::post('courses/{id}/enroll', [CourseController::class, 'enrollCourse']);
        
        // Contents
        Route::get('contents/{id}/comments', [CourseContentController::class, 'listComments']);
        Route::post('contents/{id}/comments', [CourseContentController::class, 'storeComment']);
        
        // Comments
        Route::delete('comments/{id}', [CommentController::class, 'destroy']);
        
        // Announcements
        Route::get('courses/{id}/announcements', [CourseAnnouncementController::class, 'showByCourse']);
        Route::post('announcements', [CourseAnnouncementController::class, 'store']);
        Route::put('announcements/{id}', [CourseAnnouncementController::class, 'update']);
        Route::delete('announcements/{id}', [CourseAnnouncementController::class, 'destroy']);

        // Categories
        Route::get('categories', [CourseCategoryController::class, 'index']);
        Route::post('categories', [CourseCategoryController::class, 'store']);
        Route::get('categories/{id}', [CourseCategoryController::class, 'show']);
        Route::delete('categories/{id}', [CourseCategoryController::class, 'destroy']);

        // Auth
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
