<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{

    public function upload(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'course_id' => 'required|exists:courses,id',
                'title' => 'required',
                'file' => 'required|file|mimes:pdf|max:2048'
            ]);

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('materials', $fileName, 'public');

            Material::create([
                'course_id' => $validatedData['course_id'],
                'title' => $validatedData['title'],
                'filepath' => $filePath
            ]);

            return response()->json([
                'message' => 'Material uploaded successfully',
                'filepath' => $filePath
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to upload material',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function download($id)
    {
        try {
            $material = Material::findOrFail($id);

            if (!Storage::disk('public')->exists($material->filepath)) {
                return response()->json([
                    'message' => 'File not found'
                ], 404);
            }

            return Storage::disk('public')->download($material->filepath);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to download material',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
