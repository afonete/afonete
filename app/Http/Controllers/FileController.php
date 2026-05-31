<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function download($file)
    {
        $fileContents = Storage::disk('public')->get($file);

        $headers = [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $file . '"',
        ];

        return response()->make($fileContents, 200, $headers);
    }
}
