@extends('components.layout')

@section('title')
Course Management
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('css/styleCourse.css') }}">
@endsection

@section('content')

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <span class="close-btn" onclick="closeSidebar()">×</span>
    <a href="{{ url('/admin') }}">Dashboard</a>
    <a href="{{ url('/coursedashboard') }}" class="active">Courses</a>
    <a href="{{ url('/unit') }}">Units</a>
    <a href="{{ url('/trainees') }}">Trainees</a>
    <a href="{{url('/result')}}">Results</a>
    <a href="#">Reports</a>
</aside>

<!-- Main Content -->
<div class="content">
    <button class="open-sidebar-btn" onclick="openSidebar()">☰ Open Sidebar</button>

    <!-- Page Title -->
    <h2 class="page-title">Course Management</h2>

    <!-- Buttons: Add Course & Search -->
    <div class="top-actions">
        <a href="{{ url('/addcourse') }}" class="btn btn-primary">➕ Add Course</a>
        <input type="text" id="searchCourse" onkeyup="searchCourses()" placeholder="🔍 Search Course..." class="search-box">
    </div>

<!-- Course List -->
<table class="course-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Duration</th>
            <th>Total Term</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="courseList">
    @foreach($courses as $course)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $course->course_id }}</td>
        <td>{{ $course->course_name }}</td>
        <td>{{ $course->start_year }}</td>
        <td>{{ $course->end_year }}</td>
        <td>{{ $course->duration }} Months</td>
        <td>{{ $course->total_term }}</td>
        <td>
            <!-- Edit Button -->
            <a href="{{ route('courses.edit', $course->course_id) }}" class="btn btn-edit">✏️ Edit</a>

            <!-- Delete Button -->
            <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this course?')">🗑 Delete</button>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
</div>

<!-- Add/Edit Course Modal (Hidden by Default) -->
<div id="courseModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">×</span>
        <h2 id="modalTitle">Add Course</h2>
        <form action="{{ route('courses.store') }}" method="POST">
            @csrf
            <label>Course Name:</label>
            <input type="text" name="course_name" required>

            <label>Course ID:</label>
            <input type="text" name="course_id" required>

            <label>Start Year:</label>
            <input type="number" name="start_year" required>

            <label>End Year:</label>
            <input type="number" name="end_year" required>

            <label>Duration (Months):</label>
            <input type="number" name="duration" required>

            <label>Total Terms:</label>
            <input type="number" name="total_term" required>

            <label>Description (optional):</label>
            <textarea name="description"></textarea>

            <button type="submit" class="btn btn-save">💾 Save</button>
        </form>
    </div>
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Add this line -->
<script>
    function openSidebar() {
        document.getElementById("sidebar").classList.add("open");
    }

    function closeSidebar() {
        document.getElementById("sidebar").classList.remove("open");
    }

    function searchCourses() {
        let input = document.getElementById("searchCourse").value.trim(); // Trim spaces

        if (input === "") {
            return; // Do nothing if input is empty
        }

        $.ajax({
            url: "{{ route('courses.search') }}",
            method: "GET",
            data: { search: input },
            success: function(response) {
                document.getElementById('courseList').innerHTML = response; // Update only table rows
            },
            error: function(xhr) {
                console.error("Search error:", xhr.responseJSON?.error || "Unknown error");
            }
        });
    }
</script>
@endsection