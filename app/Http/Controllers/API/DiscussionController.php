<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Discussion;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'course_id' => 'required|exists:courses,id',
                'user_id' => 'required|exists:users,id',
                'content' => 'required|string',
            ]);

            $discussion = Discussion::create($validatedData);

            return response()->json([
                'message' => 'Discussion created successfully',
                'data' => $discussion
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create discussion',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function replies(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'content' => 'required|string',
            ]);

            $discussion = Discussion::findOrFail($id);

            $reply = $discussion->replies()->create($validatedData);

            return response()->json([
                'message' => 'Reply added successfully',
                'data' => $reply
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch replies',
                'error' => $e->getMessage()
            ]);
        }
    }
}
