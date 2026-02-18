<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function courseReport()
    {
        try {
            $courses = Course::withCount('students as total_students')->get();

            return response()->json([
                'message' => 'Course report generated successfully',
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to generate course report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function assignmentReport()
    {
        try {
            $assignments = Assignment::withCount('submissions as total_submissions')->get();

            return response()->json([
                'message' => 'Assignment report generated successfully',
                'data' => $assignments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to generate assignment report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function studentReport($id)
    {
        try {
            $student = User::findOrFail($id);

            if ($student->role !== 'student') {
                throw new \Exception('User is not a student');
            }

            $submissions = Submission::where('student_id', $id);
            $total_submissions = $submissions->count();
            $average_grade = round($submissions->avg('score'), 2);

            return response()->json([
                'message' => 'Student report generated successfully',
                'data' => [
                    'student' => $student,
                    'total_submissions' => $total_submissions,
                    'average_grade' => $average_grade
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to generate student report',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
