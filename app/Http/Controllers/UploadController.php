<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    /**
     * Upload image for Summernote editor
     */
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Generate unique filename
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                
                // Store in public/uploads/guides directory
                $path = $image->storeAs('uploads/guides', $filename, 'public');
                
                // Return success response with image URL
                return response()->json([
                    'success' => true,
                    'url' => Storage::url($path),
                    'message' => 'Gambar berhasil diupload'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada file yang diupload'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload gambar: ' . $e->getMessage()
            ], 500);
        }
    }
}
