<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Log; 
use App\Models\DownloadHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    // Display all courses
    public function index()
    {
        $user = auth()->user();
        $subscription = $user->subscription; // Ensure this relationship exists
        $userDownloads = DownloadHistory::where('user_id', $user->id)->count();
        
        $courses = Course::all();

        return view('courses.index', compact('courses', 'subscription', 'userDownloads'));
    }

    // Show create course form
    public function create()
    {
        return view('courses.create');
    }

    // Store new course
    public function store(Request $request)
    {
        Log::info("Course upload request received", $request->all());
    
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'required|mimes:pdf,mp4,mkv,avi,zip,doc,docx,xls,xlsx,ppt,pptx|max:102400', 
        ]);
    
        if ($request->hasFile('file')) {
            $file = $request->file('file');
    
            // Log file details
            Log::info("File received", [
                'Original Name' => $file->getClientOriginalName(),
                'Size' => $file->getSize() . ' bytes',
                'MIME Type' => $file->getMimeType(),
            ]);
    
            // Check if file size exceeds PHP limits
            if ($file->getSize() > 100 * 1024 * 1024) { // 100MB limit
                Log::error("File too large", [
                    'Size' => $file->getSize(),
                ]);
                return back()->with('error', 'File is too large to upload.');
            }
    
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('private/courses', $filename, 'local');
    
            if (!$filePath) {
                Log::error("File storage failed");
                return back()->with('error', 'Failed to store the file.');
            }
    
            Log::info("File stored successfully", ['Path' => $filePath]);
    
            Course::create([
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $filePath,
            ]);
    
            Log::info("Course created successfully");
            return redirect()->route('courses.index')->with('success', 'Course created successfully!');
        }
    
        Log::error("File upload failed - No file detected");
        return back()->with('error', 'Failed to upload the course. Please try again.');
    }

    // Download a course file
    public function download($id)
{
    $user = auth()->user();
    $course = Course::findOrFail($id);

    // Ensure user has a subscription
    if (!$user->subscription) {
        return redirect()->route('subscriptions.index')->with('error', 'You need a subscription to download courses.');
    }

    // Check user's download limit
    $downloadCount = DownloadHistory::where('user_id', $user->id)->count();
    $planLimit = optional($user->subscription)->course_limit ?? 0; // Default to 0 if not set

    if ($downloadCount >= $planLimit) {
        Log::warning("User {$user->id} exceeded download limit.");
        return back()->with('error', 'You have reached your subscription limit. Upgrade your plan.');
    }

    // Save download history (even for duplicate downloads)
    $history = DownloadHistory::create([
        'user_id' => auth()->id(),
        'course_id' => $id,
    ]);
    
    if (!$history) {
        Log::error("Failed to insert download history", [
            'user_id' => auth()->id(),
            'course_id' => $id,
        ]);
    } else {
        Log::info("Download history inserted", [
            'user_id' => auth()->id(),
            'course_id' => $id,
        ]);
    }

    //  Correctly access private storage file
    $filePath = storage_path("app/private/{$course->file_path}");

    if (!file_exists($filePath)) {
        Log::error("File not found: {$filePath}");
        return back()->with('error', 'File not found.');
    }

    return response()->download($filePath);
}

}
