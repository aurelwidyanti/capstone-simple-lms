<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseContentFullResource;
use App\Http\Resources\CourseContentMiniResource;
use App\Http\Resources\CourseMemberResource;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\CourseMember;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $paginate = $request->query('paginate', 10);
        $courses = Course::with('teacher', 'category', 'members')->paginate($paginate);

        return response()->json([
            'status' => 'success',
            'message' => 'Courses fetched successfully',
            'data' => CourseResource::collection($courses)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id'=> 'nullable|exists:course_categories,id',
        ]);

        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'data' => $validated
            ], 400);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->store('public/courses');
            $validated['image'] = $image->hashName();
        }

        $validated['teacher_id'] =$request->user()->id;

        $course = Course::create($validated);

        $course->load('teacher', 'category', 'members');

        return response()->json([
            'status' => 'success',
            'message' => 'Course created successfully',
            'data' => new CourseResource($course)
        ], 201);
    }

    /**
     * Display the specified resource.
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show( $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found',
                'data' => null
            ], 404);
        }

        $course->load('teacher', 'category', 'members');

        return response()->json([
            'status' => 'success',
            'message' => 'Course fetched successfully',
            'data' => new CourseResource($course)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found',
                'data' => null
            ], 404);
        }

        if ($course->teacher_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to update this course',
                'data' => null
            ], 401);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id'=> 'nullable|exists:course_categories,id',
        ]);

        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'data' => $validated
            ], 400);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->store('public/courses');
            $validated['image'] = $image->hashName();
        }

        $course->update($validated);

        $course->load('teacher', 'category', 'members');

        return response()->json([
            'status' => 'success',
            'message' => 'Course updated successfully',
            'data' => new CourseResource($course)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found',
                'data' => null
            ], 404);
        }

        if ($course->teacher_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to delete this course',
                'data' => null
            ], 401);
        }

        $course->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Course deleted successfully',
            'data' => null
        ], 200);
    }

    /**
     * Get all courses created by the authenticated user.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myCourses(Request $request)
    {        
        $courses = Course::where('teacher_id', $request->user()->id)->with('teacher', 'category', 'members')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Courses fetched successfully',
            'data' => CourseResource::collection($courses)
        ], 200);
    }

    /**
     * Get all contents of a course.
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function listContent($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Course contents fetched successfully',
            'data' => CourseContentMiniResource::collection($course->contents)
        ], 200);
    }

    /**
     * Get a specific content of a course.
     * 
     * @param int $id
     * @param int $contentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function detailContent($id, $contentId)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found',
                'data' => null
            ], 404);
        }

        $content = $course->contents()->find($contentId);

        if (!$content) {
            return response()->json([
                'status' => 'error',
                'message' => 'Content not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Content fetched successfully',
            'data' => new CourseContentFullResource($content)
        ], 200);
    }

    /**
     * Enroll in a course.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function enrollCourse(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found',
                'data' => null
            ], 404);
        }

        $courseMember = CourseMember::create([
            'user_id' => $request->user()->id,
            'course_id' => $id,
            'roles' => 'std'
        ]);

       return response()->json([
            'status' => 'success',
            'message' => 'You have successfully enrolled in this course',
            'data' => new CourseMemberResource($courseMember)
        ], 200);
    }
}
