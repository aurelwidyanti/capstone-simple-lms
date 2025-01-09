<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseAnnouncementResource;
use App\Models\Course;
use App\Models\CourseAnnouncement;
use Illuminate\Http\Request;

class CourseAnnouncementController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $course = Course::find($request->course_id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        if ($request->user()->id != $course->teacher_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to create course announcements'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validated
            ], 400);
        }

        $announcement = CourseAnnouncement::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Course announcement created successfully',
            'data' => new CourseAnnouncementResource($announcement)
        ], 201);
    }

    /**
     * Display resource by course.
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showByCourse(int $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $announcements = $course->announcements;

        return response()->json([
            'status' => 'success',
            'message' => 'Course announcements retrieved successfully',
            'data' => CourseAnnouncementResource::collection($announcements)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $announcement = CourseAnnouncement::find($id);

        if (!$announcement) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course announcement not found'
            ], 404);
        }

        $course = Course::find($announcement->course_id);

        if ($request->user()->id != $course->teacher_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to update this course announcement'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validated
            ], 400);
        }

        $announcement->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Course announcement updated successfully',
            'data' => new CourseAnnouncementResource($announcement)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id)
    {
        $announcement = CourseAnnouncement::find($id);

        if (!$announcement) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course announcement not found'
            ], 404);
        }

        $course = Course::find($announcement->course_id);

        if ($request->user()->id != $course->teacher_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to delete this course announcement'
            ], 403);
        }

        $announcement->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Course announcement deleted successfully'
        ], 200);
    }
}
