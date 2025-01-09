<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
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

    public function update(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'photo' => 'nullable|image',
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

        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->description = $validated['description'];
        $user->handphone = $validated['handphone'];
        $user->email = $validated['email'];

      
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo->store('public/users');
            $user->photo = $photo->hashName();
        }

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $user
        ], 200);
    }
}
