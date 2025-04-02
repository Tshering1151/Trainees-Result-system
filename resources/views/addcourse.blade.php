@extends('components.layout')

@section('title', 'Add Course')

@section('style')
<link rel="stylesheet" href="{{ asset('css/styleCourse.css') }}">
@endsection

@section('content')

<div class="form-container">
    <h2 class="form-title">Add New Course</h2>

    <!-- Popup Success Message -->
    <div class="popup" id="successPopup" style="display: none;">
        <p id="successMessage"></p>
        <button onclick="closePopup()">OK</button>
    </div>

    <!-- Course Form -->
    <form id="courseForm" class="course-form">
        @csrf <!-- Laravel CSRF Protection -->
        
        <div class="form-group">
            <label for="courseId">Course ID:</label>
            <input type="text" id="courseId" name="course_id" placeholder="Ex(DIT2023-2025)" required>
        </div>

        <div class="form-group">
            <label for="courseName">Course Name:</label>
            <input type="text" id="courseName" name="course_name" required>
        </div>

        <div class="form-group">
            <label for="startYear">Start Year:</label>
            <input type="number" id="startYear" name="start_year" min="2000" max="2099" placeholder="YYYY" required>
        </div>

        <div class="form-group">
            <label for="endYear">End Year:</label>
            <input type="number" id="endYear" name="end_year" min="2000" max="2099" placeholder="YYYY" required>
        </div>

        <div class="form-group">
            <label for="duration">Duration (Months):</label>
            <input type="number" id="duration" name="duration" min="1" placeholder="Enter duration in months" required>
        </div>

        <div class="form-group">
            <label for="totalTerm">Total Term:</label>
            <input type="number" id="totalTerm" name="total_term" min="1" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4"></textarea>
        </div>

        <!-- Buttons -->
        <div class="button-group">
            <button type="submit" class="btn btn-save" onclick="return confirm('Are you sure you want to Add this course?')">💾 Save</button>
            <a href="{{ url('/coursedashboard') }}" class="btn btn-cancel">❌ Cancel</a>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#courseForm").submit(function(e) {
        e.preventDefault(); // Prevent normal form submission

        $.ajax({
            url: "{{ route('courses.store') }}",
            method: "POST",
            data: $(this).serialize(), // Send form data
            success: function(response) {
                // Show success popup message
                $("#successMessage").text(response.message);
                $("#successPopup").fadeIn();

                // Clear the form after success
                $("#courseForm")[0].reset();
            },
            error: function(xhr) {
                alert("Something went wrong. Please try again.");
            }
        });
    });
});

// Function to close the popup
function closePopup() {
    $("#successPopup").fadeOut();
}
</script>

@endsection
