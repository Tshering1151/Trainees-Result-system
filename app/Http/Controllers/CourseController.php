<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    // Show Courses Dashboard with the courses from the database
    public function showCourse()
    {
        $courses = Course::all(); // Retrieve all courses from the database
        return view('coursedashboard', compact('courses')); // Pass the courses to the view
    }

    public function search(Request $request)
    {
        try {
            $query = $request->input('search');
            
            // Perform the search
            $courses = Course::where('course_name', 'like', '%' . $query . '%')->get();
            
            // Return only the course rows
            return view('partials.course_rows', compact('courses'))->render();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'course_id'   => 'required|string|max:20|unique:courses,course_id',
            'course_name' => 'required|string|max:255',
            'start_year'  => 'required|integer|min:2000|max:' . date('Y'),
            'end_year'    => 'required|integer|gt:start_year|max:' . (date('Y') + 10),
            'duration'    => 'required|integer|min:1',
            'total_term'  => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        // Store in database
        Course::create([
            'course_id'   => $request->course_id,  // Admin enters manually
            'course_name' => $request->course_name,
            'start_year'  => $request->start_year,
            'end_year'    => $request->end_year,
            'duration'    => $request->duration,
            'total_term'  => $request->total_term,
            'description' => $request->description,
        ]);

         // Return JSON response for AJAX request
        return response()->json(['message' => 'New course added successfully!']);
    }

        // Functionality for deleting a course
        public function deleteCourse($id)
        {
            Course::findOrFail($id)->delete();
            return back()->with('success', 'Course deleted successfully!');
        }
}