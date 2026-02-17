<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'assignment_id' => 'required|exists:assignments,id',
                'student_id' => 'required|exists:users,id',
                'filepath' => 'required|file|mimes:pdf|max:2048',
            ]);

            $file = $request->file('filepath');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filepath = $file->storeAs('submissions', $filename, 'public');

            $validatedData['filepath'] = $filepath;

            $submission = Submission::create($validatedData);

            return response()->json([
                'message' => 'Submission submitted successfully',
                'data' => $submission,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to submit Submission',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function grade(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'score' => 'required|numeric|min:1|max:100',
            ]);

            $submission = Submission::findOrFail($id);
            $submission->score = $validatedData['score'];
            $submission->save();

            return response()->json([
                'message' => 'Submission graded successfully',
                'data' => $submission,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to grade submission',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
