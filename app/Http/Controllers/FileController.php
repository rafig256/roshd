<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class FileController extends Controller
{
    public function downloadResume($filename)
    {
        $path = 'resumes/' . $filename;
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        // بررسی دسترسی: یا ادمین است، یا فایل مربوط به خودش است
        $innovatorRequest = \App\Models\InnovatorRequest::where('resume_file_path', 'LIKE', "%{$filename}%")->first();
        
        if (!auth()->user()->hasAnyRole(['super_admin', 'site_admin', 'expert', 'pr_officer']) && auth()->id() !== $innovatorRequest?->user_id) {
            abort(403);
        }

        return response()->download(Storage::disk('public')->path($path));
    }
}