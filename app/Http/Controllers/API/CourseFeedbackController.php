<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseFeedbackResource;
use App\Models\Course;
use App\Models\CourseFeedback;
use Illuminate\Http\Request;

class CourseFeedbackController extends Controller
{
/**
     * Display a listing of the resource.
     *
     * @param int $courseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $courseId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $courseFeedbacks = CourseFeedback::with('course', 'member')->where('course_id', $courseId)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Course Feedbacks retrieved successfully',
            'data' => CourseFeedbackResource::collection($courseFeedbacks)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $courseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, int $courseId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $isMember = $course->isMember($request->user());

        if (!$isMember) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to give feedback for this course'
            ], 403);
        }

        $validated = $request->validate([
            'feedback' => 'required|string',
        ]);

        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validated
            ], 400);
        }

        $validated['member_id'] = $request->user()->id;
        $validated['course_id'] = $courseId;

        $courseFeedback = CourseFeedback::create($validated);

        $courseFeedback->load('course', 'member');

        return response()->json([
            'status' => 'success',
            'message' => 'Course Feedback created successfully',
            'data' => new CourseFeedbackResource($courseFeedback)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $courseId
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $courseId ,int $id)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $courseFeedback = CourseFeedback::find($id);

        if (!$courseFeedback) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course Feedback not found'
            ], 404);
        }

        $courseFeedback->load('course', 'member');

        return response()->json([
            'status' => 'success',
            'message' => 'Course Feedback retrieved successfully',
            'data' => new CourseFeedbackResource($courseFeedback)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $courseId
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $courseId, int $id)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $courseFeedback = CourseFeedback::find($id);

        if (!$courseFeedback) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course Feedback not found'
            ], 404);
        }

        if ($request->user()->id != $courseFeedback->member_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to update this feedback'
            ], 403);
        }

        $validated = $request->validate([
            'feedback' => 'required|string',
        ]);

        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validated
            ], 400);
        }

        $validated['member_id'] = $request->user()->id;
        
        $validated['course_id'] = $courseId;

        $courseFeedback->update($validated);

        $courseFeedback->load('course', 'member');

        return response()->json([
            'status' => 'success',
            'message' => 'Course Feedback updated successfully',
            'data' => new CourseFeedbackResource($courseFeedback)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $courseId
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $courseId, int $id)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $courseFeedback = CourseFeedback::find($id);

        if (!$courseFeedback) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course Feedback not found'
            ], 404);
        }

        if ($request->user()->id != $courseFeedback->member_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to delete this feedback'
            ], 403);
        }

        $courseFeedback->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Course Feedback deleted successfully'
        ], 200);
    }
}