<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class EditCourseController extends Controller
{
    public function edit($id)
    {
        $course = Course::where('course_id', $id)->firstOrFail(); // Fetch course by ID
        return view('editCourse', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::where('course_id', $id)->firstOrFail();

        $request->validate([
            'course_name' => 'required|string|max:255',
            'start_year'  => 'required|integer|min:2000|max:' . date('Y'),
            'end_year'    => 'required|integer|gt:start_year|max:' . (date('Y') + 10),
            'duration'    => 'required|integer|min:1',
            'total_term'  => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $course->update([
            'course_name' => $request->course_name,
            'start_year'  => $request->start_year,
            'end_year'    => $request->end_year,
            'duration'    => $request->duration,
            'total_term'  => $request->total_term,
            'description' => $request->description,
        ]);

        // AJAX request and send a success response
        return response()->json(['message' => 'Course updated successfully!']);
        
    }
}


