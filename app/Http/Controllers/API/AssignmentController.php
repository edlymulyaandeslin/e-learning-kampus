<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'course_id' => 'required|exists:courses,id',
                'title' => 'required|string|max:255',
                'description' => 'required',
                'deadline' => 'required|date',
            ]);

            $assignment =  Assignment::create($validatedData);

            return response()->json([
                'message' => 'Assignment created successfully',
                'data' => $assignment,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create assignment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
