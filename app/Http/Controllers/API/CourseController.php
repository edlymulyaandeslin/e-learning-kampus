<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $courses = Course::latest()->get();
            return response()->json([
                'message' => 'Courses retrieved successfully',
                'data' => $courses
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve courses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'lecturer_id' => 'required|exists:users,id',
                'name' => 'required',
                'description' => 'nullable',
            ]);

            $course = Course::create($validatedData);

            return response()->json([
                'message' => 'Course created successfully',
                'data' => $course
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $course = Course::findOrFail($id);

            $rules = [
                'lecturer_id' => 'required|exists:users,id',
            ];

            if ($request->filled('name')) {
                $rules['name'] = 'required';
            }
            if ($request->filled('description')) {
                $rules['description'] = 'required';
            }

            $validatedData = $request->validate($rules);

            $course->update($validatedData);

            return response()->json([
                'message' => 'Course updated successfully',
                'data' => $course
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete();

            return response()->json([
                'message' => 'Course deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function enroll($id)
    {
        try {
            $course = Course::findOrFail($id);
            $user = auth()->user();

            if ($course->students()->where('student_id', $user->id)->exists()) {
                throw new \Exception('Already enrolled in this course');
            }

            $course->students()->attach($user->id);

            return response()->json([
                'message' => 'Enrolled in course successfully',
                'data' => [
                    'course_id' => $course->id,
                    'student_id' => $user->id,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to enroll in course',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
