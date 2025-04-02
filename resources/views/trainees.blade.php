@extends('components.layout')

@section('title')
Trainee Management
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('css/styleCourse.css') }}">
@endsection

@section('content')

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <span class="close-btn" onclick="closeSidebar()">×</span>
    <a href="{{ url('/admin') }}">Dashboard</a>
    <a href="{{ url('/coursedashboard') }}">Courses</a>
    <a href="{{ url('/unit') }}">Units</a>
    <a href="{{ url('/trainees') }}" class="active">Trainees</a>
    <a href="{{url('/result')}}">Results</a>
    <a href="#">Reports</a>
</aside>

<!-- Main Content -->
<div class="content">
    <button class="open-sidebar-btn" onclick="openSidebar()">☰ Open Sidebar</button>

    <h2 class="page-title">Trainee Management</h2>

    <!-- Search Box -->
<input type="text" id="searchCourseYear" placeholder="Enter Start Year of Course to Add Trainees..." onblur="searchCourses()">
<button id="courseResults" class="btn btn-primary">
    Search
</button>

<!-- Options Section (Initially Hidden) -->
<div id="traineeOptions" style="display: none;">
    <h3>Manage Trainees for <span id="selectedCourseName"></span></h3>
    <button onclick="addIndividualTrainee()" class="btn btn-primary">➕ Add Individual Trainee</button>
    <button onclick="uploadCSV()" class="btn btn-primary">📂 Upload CSV</button>
</div>

<script>
function searchCourses() {
    let startYear = document.getElementById("searchCourseYear").value;

    if (startYear) {
        fetch(`{{ route('trainees.searchCourses') }}?start_year=${startYear}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById("courseResults").innerHTML = data;
            })
            .catch(error => console.error("Search error:", error));
    }
}

// Function to open options when clicking a course
function openTraineeOptions(courseId, courseName) {
    document.getElementById("selectedCourseName").innerText = courseName;
    document.getElementById("traineeOptions").style.display = "block";

    // Store the selected courseId for further actions
    sessionStorage.setItem("selectedCourseId", courseId);
}

// Navigate to add individual trainee
function addIndividualTrainee() {
    let courseId = sessionStorage.getItem("selectedCourseId");
    if (courseId) {
        window.location.href = `/add-trainee/${courseId}`;
    }
}

// Navigate to upload CSV page
function uploadCSV() {
    let courseId = sessionStorage.getItem("selectedCourseId");
    if (courseId) {
        window.location.href = `/upload-trainees/${courseId}`;
    }
}
</script>
@endsection
