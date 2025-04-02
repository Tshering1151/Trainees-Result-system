<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Course;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    // Display Units
    public function index()
    {
        $units = Unit::all();  
        $courses = Course::all();  
        return view('unit', compact('units', 'courses'));  
    }

    // Show Add Unit Form
    public function addUnits($courseId, $term)
    {
        $course = Course::findOrFail($courseId);
        return view('addunits', compact('course', 'term')); 
    }

    // Save Units to Database
    public function saveUnits(Request $request)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'course_id' => 'required|string|exists:courses,course_id', // Validate course_id as a string and it should exist in the courses table
            'term' => 'required|integer',
            'units.*.unit_name' => 'required|string',
            'units.*.lecture' => 'required|string',
            'units.*.description' => 'nullable|string',
        ]);

        // Loop through the units and save them to the database
        foreach ($validatedData['units'] as $unitData) {
            Unit::create([
                'course_id' => $validatedData['course_id'],
                'term' => $validatedData['term'],
                'unit_name' => $unitData['unit_name'],
                'lecture' => $unitData['lecture'],
                'description' => $unitData['description'],
            ]);
        }

        // Redirect or return a success response
        return redirect()->back()->with('success', 'Units saved successfully!');
    }
}
