<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AddCourseController;
use App\Http\Controllers\EditCourseController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\TraineesController;
use App\Http\Controllers\ResultController;

// Home Route
Route::get('/', function () {
    return view('welcome'); // Main landing page
})->name('home');

// Trainee Result Page (No login required)
Route::get('/trainee/result', function () {
    return view('trainee.result'); // Ensure this view exists
})->name('trainee.result');

// Admin Login Page
Route::get('/admin/login', function () {
    return view('auth.admin-login'); 
})->name('admin.login');

// Admin Login Processing
Route::post('/admin/login', function (Request $request) {
    $fixedUsername = 'admin';
    $fixedPassword = 'admin123';

    if ($request->username === $fixedUsername && $request->password === $fixedPassword) {
        // Store admin session
        Session::put('admin', true);
        return redirect()->route('admin.page'); // Redirect to admin dashboard
    } else {
        return back()->with('error', 'Invalid username or password.');
    }
})->name('admin.login.submit');

// Admin Dashboard Page
Route::get('/admin', function () {
    if (!Session::has('admin')) {
        return redirect()->route('admin.login')->with('error', 'Please log in first.');
    }
    return view('admin'); // Make sure admin.blade.php exists
})->name('admin.page');

// Admin Logout
Route::post('/admin/logout', function () {
    Session::forget('admin');
    return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
})->name('admin.logout');

Route::get('/admin',[HomeController::class, 'Dashboard']); /** To go inside Home */

Route::get('/coursedashboard',[CourseController::class, 'showCourse'])->name('courses.create'); /** To go inside coursedashboard */
Route::get('/coursedashboard', [CourseController::class, 'showCourse'])->name('coursedashboard');
Route::post('/courses/store', [CourseController::class, 'store'])->name('courses.store');
Route::get('/addcourse',[AddCourseController::class, 'AddCourse']); /**To go inside add course*/
Route::get('/coursedashboard/search', [CourseController::class, 'search'])->name('courses.search'); // Route to handle search request
Route::get('/courses/edit/{id}', [EditCourseController::class, 'edit'])->name('courses.edit');
Route::put('/courses/update/{id}', [EditCourseController::class, 'update'])->name('courses.update');

Route::get('/unit', [UnitController::class, 'index'])->name('unit.index');  // Display Units
Route::get('/addunits/{courseId}/{term}', [UnitController::class, 'addUnits'])->name('unit.add');  // Show Add Unit Form
Route::post('/saveunits', [UnitController::class, 'saveUnits'])->name('unit.save');  // Save Units to Database


Route::delete('/course/{id}', [CourseController::class, 'deleteCourse'])->name('courses.delete');

Route::get('/trainees', [TraineesController::class, 'index'])->name('trainees.index');  // Display Trainees
Route::get('/add-trainee/{courseId}', [TraineesController::class, 'addTrainee'])->name('trainees.add');
Route::get('/upload-trainees/{courseId}', [TraineesController::class, 'uploadTrainees'])->name('trainees.upload');
Route::get('/trainees/search-courses', [TraineesController::class, 'searchCourses'])->name('trainees.searchCourses');
Route::post('/trainees/store', [TraineesController::class, 'store'])->name('trainees.store');
Route::get('/trainees/getByCourse', [TraineesController::class, 'getByCourse'])->name('trainees.getByCourse');
Route::get('/trainees/{trainee}/edit', [TraineesController::class, 'edit'])->name('trainees.edit');
Route::delete('/trainees/{trainee}', [TraineesController::class, 'destroy'])->name('trainees.destroy');
Route::post('/upload-trainees/{course_id}', [TraineesController::class, 'uploadCSV'])
    ->name('trainee.upload_csv');
Route::get('/download-trainees-template', [TraineesController::class, 'downloadTemplate'])
    ->name('trainee.download_template');

// Results management routes
Route::get('/result', [ResultController::class, 'index'])->name('results.index');
Route::get('/course-details/{courseId}', [ResultController::class, 'getCourseDetails']);
Route::get('/validate-rim-id/{rimId}', [ResultController::class, 'validateRimId']);
Route::get('/add_result', [ResultController::class, 'showAddResultForm'])->name('results.add_form');
Route::post('/results/store', [ResultController::class, 'store'])->name('results.store');
Route::post('/results/prepare-upload', [ResultController::class, 'prepareUpload'])->name('results.prepare_upload');
Route::get('/results/template', [ResultController::class, 'downloadTemplate'])->name('results.template');
Route::get('/upload_result/{uploadId}', [ResultController::class, 'showUploadForm'])->name('results.upload_form');
Route::post('/results/import', [ResultController::class, 'import'])->name('results.import');