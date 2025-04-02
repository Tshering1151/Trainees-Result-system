@extends('components.layout')

@section('title', 'Add Result')

@section('style')
<link rel="stylesheet" href="{{ asset('css/styleCourse.css') }}">
<style>
    .unit-mark-form {
        margin-top: 20px;
    }
    
    .unit-item {
        background-color: #f9f9f9;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 5px;
        border-left: 4px solid #4e73df;
    }
    
    .unit-title {
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    .mark-input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    
    .form-actions {
        margin-top: 25px;
        display: flex;
        justify-content: space-between;
    }
    
    .trainee-info {
        background-color: #e8f4ff;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
    }
    
    .trainee-info h3 {
        margin-top: 0;
        color: #333;
    }
    
    .trainee-detail {
        margin-bottom: 5px;
    }
    
    .error-feedback {
        color: #dc3545;
        font-size: 0.9rem;
        margin-top: 5px;
    }
    
    .validation-passed {
        color: #28a745;
    }
</style>
@endsection

@section('content')

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <span class="close-btn" onclick="closeSidebar()">×</span>
    <a href="{{ url('/admin') }}">Dashboard</a>
    <a href="{{ url('/coursedashboard') }}">Courses</a>
    <a href="{{ url('/unit') }}">Units</a>
    <a href="{{ url('/trainees') }}">Trainees</a>
    <a href="{{ url('/result') }}" class="active">Results</a>
    <a href="#">Reports</a>
</aside>

<!-- Main Content -->
<div class="content">
    <button class="open-sidebar-btn" onclick="openSidebar()">☰ Open Sidebar</button>

    <h2 class="page-title">Add Individual Result</h2>
    
    <div class="trainee-info">
        <h3>Selected Details</h3>
        <div class="trainee-detail"><strong>Course ID:</strong> {{ $course->course_id ?? request()->query('course_id') }}</div>
        <div class="trainee-detail"><strong>Course Name:</strong> {{ $course->course_name ?? '' }}</div>
        <div class="trainee-detail"><strong>RIM ID:</strong> {{ $trainee->rim_id ?? request()->query('rim_id') }}</div>
        <div class="trainee-detail"><strong>Trainee Name:</strong> {{ $trainee->name ?? '' }}</div>
        <div class="trainee-detail"><strong>Term:</strong> {{ request()->query('term') }}</div>
    </div>

    <form method="POST" action="{{ route('results.store') }}" class="unit-mark-form">
        @csrf
        <input type="hidden" name="course_id" value="{{ request()->query('course_id') }}">
        <input type="hidden" name="rim_id" value="{{ request()->query('rim_id') }}">
        <input type="hidden" name="term" value="{{ request()->query('term') }}">
        
        @if(isset($units) && count($units) > 0)
            @foreach($units as $unit)
                <div class="unit-item">
                    <div class="unit-title">{{ $unit->unit_name }}</div>
                    <input type="hidden" name="unit_ids[]" value="{{ $unit->id }}">
                    <label for="mark_{{ $unit->id }}">Enter Mark:</label>
                    <input 
                        type="number" 
                        id="mark_{{ $unit->id }}" 
                        name="marks[{{ $unit->id }}]" 
                        class="mark-input" 
                        min="0" 
                        max="100" 
                        step="0.1" 
                        required
                        onchange="validateMark(this)"
                    >
                    <div class="error-feedback" id="error_{{ $unit->id }}"></div>
                </div>
            @endforeach
            
            <div class="form-actions">
                <a href="{{ url('/result') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Result</button>
            </div>
        @else
            <div class="alert alert-warning">
                No units found for this course and term. Please check your selection or add units first.
            </div>
            <div class="form-actions">
                <a href="{{ url('/result') }}" class="btn btn-secondary">Back to Results</a>
            </div>
        @endif
    </form>
</div>

<script>
    // Function to validate mark inputs
    function validateMark(input) {
        const value = parseFloat(input.value);
        const unitId = input.id.replace('mark_', '');
        const errorElement = document.getElementById('error_' + unitId);
        
        if (isNaN(value)) {
            errorElement.textContent = 'Please enter a valid number';
            input.classList.add('invalid');
            return false;
        } else if (value < 0 || value > 100) {
            errorElement.textContent = 'Mark must be between 0 and 100';
            input.classList.add('invalid');
            return false;
        } else {
            errorElement.textContent = '✓';
            errorElement.classList.add('validation-passed');
            input.classList.remove('invalid');
            return true;
        }
    }
    
    // Sidebar controls
    function openSidebar() {
        document.getElementById("sidebar").style.width = "250px";
    }
    
    function closeSidebar() {
        document.getElementById("sidebar").style.width = "0";
    }
</script>

@endsection