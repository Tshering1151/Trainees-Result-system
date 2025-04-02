@extends('components.layout')

@section('title', 'Units')

@section('style')
<link rel="stylesheet" href="{{ asset('css/styleCourse.css') }}">
@endsection

@section('content')

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <span class="close-btn" onclick="closeSidebar()">×</span>
    <a href="{{ url('/admin') }}">Dashboard</a>
    <a href="{{ url('/coursedashboard') }}">Courses</a>
    <a href="{{ url('/unit') }}" class="active">Units</a>
    <a href="{{ url('/trainees') }}">Trainees</a>
    <a href="{{url('/result') }}">Results</a>
    <a href="#">Reports</a>
</aside>

<!-- Main Content -->
<div class="content">
    <button class="open-sidebar-btn" onclick="openSidebar()">☰ Open Sidebar</button>

    <h2 class="page-title">Unit Management</h2>

    <!-- Add Unit Section -->
    <div class="top-actions">
        <button class="btn btn-primary" onclick="openAddUnitModal()">➕ Add Unit</button>
        <input type="text" id="searchUnits" onkeyup="searchUnits()" placeholder="🔍 Search Unit..." class="search-box">
    </div>

    <!-- Unit List -->
    <table class="course-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Unit Name</th>
                <th>Lecture</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="unitList">
            @foreach($units as $unit)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $unit->unit_name }}</td>
                <td>{{ $unit->lecture }}</td>
                <td>
                    <button class="btn btn-edit">✏ Edit</button>
                    <button class="btn btn-delete">🗑 Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Unit Modal -->
<div id="addUnitModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close-modal" onclick="closeAddUnitModal()">×</span>
        <h2>Select Course & Term</h2>

        <label for="courseSelect">Select Course ID:</label>
        <select id="courseSelect">
            <option value="">-- Select Course --</option>
            @foreach($courses as $course)
            <option value="{{ $course->course_id }}" data-terms="{{ $course->total_term }}">
                {{ $course->course_id }} <!-- Displaying Course ID  -->
            </option>
            @endforeach
        </select>

        <label for="termSelect">Select Term:</label>
        <select id="termSelect">
            <option value="">-- Select Term --</option>
        </select>

        <button class="btn btn-primary" onclick="redirectToAddUnitPage()">Add Unit</button>
    </div>
</div>

@endsection

@section('script')
<script>
    function openAddUnitModal() {
        let modal = document.getElementById("addUnitModal");
        if (modal) {
            modal.style.display = "block";
        }
    }

    function closeAddUnitModal() {
        let modal = document.getElementById("addUnitModal");
        if (modal) {
            modal.style.display = "none";
        }
    }

    // Ensure DOM is loaded before attaching event listeners
    document.addEventListener("DOMContentLoaded", function () {
        let courseSelect = document.getElementById("courseSelect");
        let termSelect = document.getElementById("termSelect");

        if (courseSelect) {
            courseSelect.addEventListener("change", function () {
                let selectedCourseId = this.value; 
                let selectedOption = this.options[this.selectedIndex];
                let totalTerms = selectedOption.getAttribute("data-terms");

                termSelect.innerHTML = "<option value=''>-- Select Term --</option>";

                if (totalTerms) {
                    for (let i = 1; i <= totalTerms; i++) {
                        termSelect.innerHTML += `<option value="${i}">Term ${i}</option>`;
                    }
                }
            });
        }

        window.redirectToAddUnitPage = function () {
            let courseId = courseSelect.value;
            let term = termSelect.value;

            if (!courseId || !term) {
                alert("Please select both Course and Term.");
                return;
            }

            window.location.href = `/addunits/${courseId}/${term}`;
        };
    });
</script>
@endsection
