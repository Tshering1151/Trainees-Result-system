<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddCourseController extends Controller
{
    public function AddCourse()
    {
        return view('addcourse');
    }
}
