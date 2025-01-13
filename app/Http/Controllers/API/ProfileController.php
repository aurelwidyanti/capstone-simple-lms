<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the user profile
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $courseCreated = $request->user()->courses;

        $courseJoined = $request->user()->courseMembers;

        return response()->json([
            'status' => 'success',
            'message' => 'Profile retrieved successfully',
            'data' => [
                'user' => $request->user(),
                'course_created' => $courseCreated,
                'course_joined' => $courseJoined
            ]
        ], 200);
    }

    /**
     * Update the user profile
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'photo' => 'nullable|string',
            'description' => 'nullable|string',
            'handphone' => 'nullable|string',
        ]);

        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'data' => $validated
            ], 400);
        }

        $user = $request->user();

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo->store('public/users');
            $user->photo = $photo->hashName();
        }

        $user->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $user
        ], 200);
    }
}
