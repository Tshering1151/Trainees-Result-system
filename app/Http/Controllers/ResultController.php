<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Trainee;
use App\Models\Unit;
use App\Models\Result;
use Illuminate\Support\Facades\Validator;

class ResultController extends Controller
{
    public function index()
    {
        // Display the results page
        return view('result');
    }
    
    // Validate course ID and get details
    public function getCourseDetails($courseId)
    {
        $course = Course::where('course_id', $courseId)->first();
        
        if ($course) {
            return response()->json([
                'exists' => true,
                'total_term' => $course->total_term
            ]);
        }
        
        return response()->json([
            'exists' => false
        ]);
    }
    
    // Validate RIM ID
    public function validateRimId($rimId)
    {
        $trainee = Trainee::where('rim_id', $rimId)->first();
        
        return response()->json([
            'exists' => $trainee ? true : false
        ]);
    }
    
    // Show the form to add individual result
    public function showAddResultForm(Request $request)
    {
        $courseId = $request->query('course_id');
        $rimId = $request->query('rim_id');
        $term = $request->query('term');
        
        // Validate input parameters
        if (!$courseId || !$rimId || !$term) {
            return redirect()->route('results.index')
                ->with('error', 'Missing required parameters');
        }
        
        // Get course and trainee details
        $course = Course::where('course_id', $courseId)->first();
        $trainee = Trainee::where('rim_id', $rimId)->first();
        
        if (!$course || !$trainee) {
            return redirect()->route('results.index')
                ->with('error', 'Course or Trainee not found');
        }
        
        // Get units for the selected course and term
        $units = Unit::where('course_id', $courseId)
                    ->where('term', $term)
                    ->get();
        
        return view('add_result', compact('course', 'trainee', 'units'));
    }
    
    // Store the result
    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            'rim_id' => 'required|exists:trainees,rim_id',
            'term' => 'required|numeric',
            'unit_ids' => 'required|array',
            'marks' => 'required|array',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Process each unit mark
        foreach ($request->unit_ids as $index => $unitId) {
            $mark = $request->marks[$unitId] ?? null;
            
            if ($mark !== null) {
                // Check if result already exists
                $result = Result::where('course_id', $request->course_id)
                            ->where('rim_id', $request->rim_id)
                            ->where('unit_id', $unitId)
                            ->first();
                
                if ($result) {
                    // Update existing result
                    $result->mark = $mark;
                    $result->save();
                } else {
                    // Create new result
                    Result::create([
                        'course_id' => $request->course_id,
                        'rim_id' => $request->rim_id,
                        'unit_id' => $unitId,
                        'term' => $request->term,
                        'mark' => $mark
                    ]);
                }
            }
        }
        
        return redirect()->route('results.index')
            ->with('success', 'Results saved successfully');
    }
    
    // Prepare CSV upload
    public function prepareUpload(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,course_id',
            'term' => 'required|numeric',
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }
        
        // Generate a unique upload ID
        $uploadId = uniqid('upload_');
        
        // Store the file temporarily
        $path = $request->file('csv_file')->storeAs(
            'csv_uploads',
            $uploadId . '.csv',
            'public'
        );
        
        // Store upload details in session
        session()->put('csv_upload_' . $uploadId, [
            'course_id' => $request->course_id,
            'term' => $request->term,
            'file_path' => $path
        ]);
        
        return response()->json([
            'success' => true,
            'upload_id' => $uploadId
        ]);
    }
    
    // Show CSV template
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=result_template.csv',
        ];
        
        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['rim_id', 'unit_1_mark', 'unit_2_mark', 'unit_3_mark', 'unit_4_mark']);
            fputcsv($file, ['1001', '85.5', '90', '78.5', '92']);
            fputcsv($file, ['1002', '76', '82', '90', '88']);
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}