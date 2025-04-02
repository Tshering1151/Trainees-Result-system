<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Trainee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel; // Import Excel Facade if you're using Laravel Excel for CSV parsing
use App\Imports\TraineesImport; // Create this import class for processing the CSV data
use Illuminate\Support\Facades\Response;

class TraineesController extends Controller
{
    public function index()
    {
        // $trainees = Trainee::all();
        return view('trainees'); //compact('trainees'));
    }

    public function searchCourses(Request $request)
    {
        $startYear = $request->input('start_year');
        $courses = Course::where('start_year', $startYear)->get();

        if ($courses->isEmpty()) {
            return "<p>No courses found for this year.</p>";
        }

        $html = "<ul class='course-list'>";
        foreach ($courses as $course) {
            $html .= "<li onclick=\"openTraineeOptions('{$course->course_id}', '{$course->course_id}')\">{$course->course_id}</li>";
        }
        $html .= "</ul>";

        return $html;
    }

    public function addTrainee($courseId)
    {
        return view('add_trainees', compact('courseId'));
    }

    public function store(Request $request)
    {
        // Validate input fields
        $request->validate([
            'rim_id' => 'required|string|unique:trainees,rim_id',  // Check if rim_id is unique
            'name' => 'required|string|max:255',
            'cid' => 'required|string|unique:trainees,cid',
            'email' => 'required|email|unique:trainees,email',  // Email must be unique
            'contact_no' => [
                'required',
                'regex:/^(17|77)\d{6}$/', // Ensure starts with 17 or 77 and has exactly 8 digits
                'size:8' // This ensures the number is exactly 8 digits long
            ],
            'emergency_contact' => [
                'required',
                'regex:/^(17|77)\d{6}$/', // Ensure starts with 17 or 77 and has exactly 8 digits
                'size:8' // This ensures the number is exactly 8 digits long
            ],
            'address' => 'required|string|max:255',
        ], [
            'contact_no.regex' => 'The contact number must start with 17 or 77 and be exactly 8 digits.',
            'contact_no.size' => 'The contact number must be exactly 8 digits.',
            'emergency_contact.regex' => 'The emergency contact must start with 17 or 77 and be exactly 8 digits.',
            'emergency_contact.size' => 'The emergency contact must be exactly 8 digits.',
            'email.unique' => 'The email address has already been registered.',
            'rim_id.unique' => 'The RIM ID has already been registered.',  // Custom message for duplicate rim_id
        ]);

        // Store new trainee in the database
        Trainee::create([
            'rim_id' => $request->rim_id,
            'name' => $request->name,
            'course_name' => $request->course_id, // Assuming course_id is used as course_name here
            'cid' => $request->cid,
            'email' => $request->email,
            'contact' => $request->contact_no,
            'emergency_contact' => $request->emergency_contact,
            'address' => $request->address,
        ]);

        // Return success response
        return redirect()->back()->with('success', 'Trainee added successfully!');
    }

    public function uploadTrainees($course_id)
    {
        return view('upload_trainees', compact('course_id'));
    }

    public function showUploadForm($courseId)
    {
        $course = Course::findOrFail($courseId); // Fetch course details
        return view('upload_trainees', ['course_name' => $course->course_name]);
    }

    public function uploadCSV(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',  // Max file size 2MB
        ]);

        // Retrieve the course_id from the form data
        $courseId = $request->input('course_id');
        $course = Course::find($courseId);

        if (!$course) {
            return back()->with('error', 'Course not found');
        }

        $courseName = $course->course_name;

        // Handle the file upload
        if ($request->hasFile('csv_file') && $request->file('csv_file')->isValid()) {
            $file = $request->file('csv_file');
            $filename = 'trainees_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('csv_uploads', $filename, 'public');
            $fullPath = storage_path('app/public/' . $filePath);

            // Check if file exists before attempting to open it
            if (file_exists($fullPath)) {
                $data = array_map('str_getcsv', file($fullPath));

                // Remove the header row if present
                if (!empty($data) && count($data[0]) > 1) {
                    array_shift($data);
                }

                // Prepare arrays to track existing and invalid trainees
                $existingTrainees = [];
                $invalidTrainees = [];
                $successfulTrainees = [];

                foreach ($data as $row) {
                    // Validate each row
                    $validationResult = $this->validateTraineeRow($row);

                    if ($validationResult['valid']) {
                        // Check if trainee already exists
                        $existingTrainee = $this->checkExistingTrainee($row);

                        if ($existingTrainee) {
                            $existingTrainees[] = [
                                'data' => $row,
                                'existing_record' => $existingTrainee
                            ];
                        } else {
                            // Save new trainee
                            try {
                                $trainee = Trainee::create([
                                    'rim_id' => $row[0],
                                    'name' => $row[1],
                                    'course_name' => $courseName,
                                    'cid' => $row[2],
                                    'email' => $row[3],
                                    'contact' => $row[4],
                                    'emergency_contact' => $row[5],
                                    'address' => $row[6],
                                ]);
                                $successfulTrainees[] = $trainee;
                            } catch (\Exception $e) {
                                Log::error('Error inserting trainee data:', [
                                    'error' => $e->getMessage(), 
                                    'row' => $row
                                ]);
                            }
                        }
                    } else {
                        $invalidTrainees[] = [
                            'data' => $row,
                            'errors' => $validationResult['errors']
                        ];
                    }
                }

                // Prepare return message
                $returnData = [
                    'successful_count' => count($successfulTrainees),
                    'existing_count' => count($existingTrainees),
                    'invalid_count' => count($invalidTrainees)
                ];

                // Store detailed results in session
                $request->session()->flash('upload_results', [
                    'existing_trainees' => $existingTrainees,
                    'invalid_trainees' => $invalidTrainees,
                    'successful_trainees' => $successfulTrainees
                ]);

                // Redirect with success message
                return redirect()->back()->with([
                    'success' => $this->generateUploadSummary($returnData),
                    'show_details' => true
                ]);
            } else {
                return back()->with('error', 'Failed to open CSV file');
            }
        }

        return back()->with('error', 'Invalid file upload');
    }

    /**
     * Validate individual trainee row
     */
    private function validateTraineeRow($row)
    {
        // Ensure row has correct number of elements
        if (count($row) < 7) {
            return [
                'valid' => false,
                'errors' => ['Insufficient data in row']
            ];
        }

        $validator = Validator::make([
            'rim_id' => $row[0],
            'name' => $row[1],
            'cid' => $row[2],
            'email' => $row[3],
            'contact' => $row[4],
            'emergency_contact' => $row[5],
            'address' => $row[6]
        ], [
            'rim_id' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'cid' => [
                'required', 
                'string', 
                'size:11',
                'regex:/^\d{11}$/'
            ],
            'email' => 'required|email|max:255',
            'contact' => [
                'required', 
                'string', 
                'size:8', 
                'regex:/^(17|77)\d{6}$/'
            ],
            'emergency_contact' => [
                'required', 
                'string', 
                'size:8', 
                'regex:/^(17|77)\d{6}$/'
            ],
            'address' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return [
                'valid' => false,
                'errors' => $validator->errors()->all()
            ];
        }

        return ['valid' => true, 'errors' => []];
    }

    /**
     * Check if trainee already exists in database
     */
    private function checkExistingTrainee($row)
    {
        return Trainee::where('rim_id', $row[0])
            ->orWhere('email', $row[3])
            ->orWhere('cid', $row[2])
            ->first();
    }

    /**
     * Generate upload summary message
     */
    private function generateUploadSummary($data)
    {
        $message = "Upload Summary: ";
        $message .= "{$data['successful_count']} trainees added successfully. ";
        
        if ($data['existing_count'] > 0) {
            $message .= "{$data['existing_count']} trainees already exist. ";
        }
        
        if ($data['invalid_count'] > 0) {
            $message .= "{$data['invalid_count']} trainees had validation errors.";
        }

        return $message;
    }

    public function downloadTemplate()
    {
        // Define the CSV headers
        $headers = [
            'RIM ID', 
            'Name', 
            'CID', 
            'Email', 
            'Contact', 
            'Emergency Contact', 
            'Address'
        ];

        // Create a temporary file
        $filename = 'trainees_template.csv';
        $handle = fopen($filename, 'w+');
        
        // Add headers to the CSV
        fputcsv($handle, $headers);
        
        // Close the file
        fclose($handle);

        // Download the file
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="trainees_template.csv"',
        ];

        return Response::download($filename, 'trainees_template.csv', $headers)->deleteFileAfterSend(true);
    }

}
