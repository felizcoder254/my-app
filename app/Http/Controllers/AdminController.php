<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Show the “Admin File Manager” page (resources/views/admin/list.blade.php).
     */
    public function listFiles()
    {
        return view('admin.list');
    }

    /**
     * (Optional) If you have a separate upload form, return it.
     * Otherwise, you are already using the integrated form in list.blade.php.
     */
    public function showUploadForm()
    {
        return view('admin.upload');
    }

    /**
     * Handle multi‐file uploads from the admin form.
     *
     * We expect:
     *   • files[]  => array of PDF/DOCX files
     *   • paper    => one of [1,2,past-papers,prediction]
     *   • category => string (e.g. “Communicable & Tropical Diseases”)
     */
    public function upload(Request $request)
    {
        // 1) Validate that “files” is present and each entry is a PDF or DOCX ≤10MB
        $request->validate([
            'files'   => 'required|array',
            'files.*' => 'file|mimes:pdf,docx|max:10240',
            'paper'   => 'required|in:1,2,past-papers,prediction',
            'category'=> 'required|string',
        ]);

        // 2) Map the “paper” key to the actual folder under nck/
        $paperMap = [
            '1'           => 'paper_1',
            '2'           => 'paper_2',
            'past-papers' => 'pastpapers',
            'prediction'  => 'predictions',
        ];

        $paperKey   = $request->input('paper');         // e.g. "1" or "past-papers"
        $paperDir   = $paperMap[$paperKey];             // e.g. "paper_1" or "pastpapers"
        $category   = trim($request->input('category'));
        $uploadedFiles = $request->file('files');       // array of UploadedFile objects

        // 3) Build the disk‐relative folder under storage/app/public
        $targetDir = "nck/{$paperDir}/{$category}";

        // Ensure that folder exists on the “public” disk
        if (! Storage::disk('public')->exists($targetDir)) {
            Storage::disk('public')->makeDirectory($targetDir);
        }

        // 4) Loop over each uploaded file and store it
        $storedNames = [];
        foreach ($uploadedFiles as $uploaded) {
            $originalName = $uploaded->getClientOriginalName();
            $filename     = time() . '_' . preg_replace('/\s+/', '_', $originalName);
            $storedPath   = $uploaded->storeAs($targetDir, $filename, 'public');

            if ($storedPath) {
                $storedNames[] = $filename;
            }
        }

        // 5) If at least one file was stored, flash success
        if (count($storedNames) > 0) {
            $count = count($storedNames);
            return back()->with('success', "Uploaded {$count} file(s) to {$paperDir}/{$category}.");
        }

        // 6) Otherwise, something went wrong
        return back()->with('error', 'Failed to upload files. Please try again.');
    }

    /**
     * Handle deletion of a single file OR an entire category folder.
     *
     * Blade’s form sends “path” = either:
     *   • "nck/paper_1/SomeCategory/filename.pdf"
     *   • "nck/paper_1/SomeCategory"
     *
     * We strip any leading “public/” and then remove it from the public disk.
     */
    public function delete(Request $request)
    {
        // 1) Only allow admins to delete
        $user = Auth::user();
        if (! $user || ! $user->is_admin) {
            abort(403, 'This action is unauthorized.');
        }

        // 2) Validate that “path” was submitted
        $data = $request->validate([
            'path' => 'required|string',
        ]);

        // 3) Get the raw path
        $rawPath = $data['path'];
        // e.g. "public/nck/paper_1/SomeCategory/filename.pdf"
        // or   "nck/paper_1/SomeCategory"

        // 4) Strip "public/" if present
        if (str_starts_with($rawPath, 'public/')) {
            $relativePath = substr($rawPath, strlen('public/'));
        } else {
            $relativePath = $rawPath;
        }
        // Now: "nck/paper_1/SomeCategory/filename.pdf" or "nck/paper_1/SomeCategory"

        $disk = Storage::disk('public');

        // 5) If it's a directory, delete the entire folder
        if ($disk->directoryExists($relativePath)) {
            $disk->deleteDirectory($relativePath);
            return back()->with('success', 'Category deleted: ' . $relativePath);
        }

        // 6) Otherwise, if it’s a file, delete that single file
        if ($disk->exists($relativePath)) {
            $disk->delete($relativePath);
            return back()->with('success', 'File deleted: ' . basename($relativePath));
        }

        // 7) If neither exists, flash an error
        return back()->with('error', 'Path not found: ' . $relativePath);
    }

    /**
     * (Optional) Admin analytics/dashboard page.
     */
    public function dashboard()
    {
        $user = Auth::user();
        if (! $user || ! $user->is_admin) {
            abort(403, 'This action is unauthorized.');
        }
        return view('admin.analytics');
    }
}
