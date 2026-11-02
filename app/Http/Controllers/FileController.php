<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class FileController extends Controller
{
    public function downloadResume($filename)
    {
        $path = Storage::disk('public')->path('resumes/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }
}